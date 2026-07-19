<?php

namespace App\Messenger\Transport;

use App\Messenger\Stamp\BrokerPassportStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Topology twin RabbitMQ — exchange / queue / routing key v SQLite.
 * Stejný mental model jako broker; bez Dockeru i bez ext-amqp.
 */
final class AmqpSimTransport implements TransportInterface, MessageCountAwareInterface
{
    private \PDO $pdo;
    private bool $setupDone = false;

    public function __construct(
        private SerializerInterface $serializer,
        private string $vhost,
        private string $exchange,
        private string $queue,
        private string $routingKey,
    ) {
        $dir = dirname(__DIR__, 3).'/var';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->pdo = new \PDO('sqlite:'.$dir.'/amqp_sim.db');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function send(Envelope $envelope): Envelope
    {
        $this->setup();
        $encoded = $this->serializer->encode($envelope);
        $stmt = $this->pdo->prepare(
            'INSERT INTO amqp_sim_message (vhost, exchange, queue, routing_key, body, headers, created_at)
             VALUES (:vhost, :exchange, :queue, :rk, :body, :headers, :created)'
        );
        $stmt->execute([
            'vhost' => $this->vhost,
            'exchange' => $this->exchange,
            'queue' => $this->queue,
            'rk' => $this->routingKey,
            'body' => $encoded['body'],
            'headers' => json_encode($encoded['headers'] ?? [], JSON_THROW_ON_ERROR),
            'created' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
        $id = (int) $this->pdo->lastInsertId();

        return $envelope
            ->with(new TransportMessageIdStamp($id))
            ->with(new BrokerPassportStamp('async', 'amqpsim', $this->exchange, $this->queue, $this->routingKey));
    }

    public function get(): iterable
    {
        $this->setup();
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->query(
                'SELECT id, body, headers, exchange, queue, routing_key
                 FROM amqp_sim_message
                 WHERE queue = '.$this->pdo->quote($this->queue).'
                   AND delivered_at IS NULL
                 ORDER BY id ASC
                 LIMIT 1'
            );
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                $this->pdo->commit();

                return [];
            }

            $upd = $this->pdo->prepare('UPDATE amqp_sim_message SET delivered_at = :d WHERE id = :id');
            $upd->execute([
                'd' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                'id' => $row['id'],
            ]);
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        $envelope = $this->serializer->decode([
            'body' => $row['body'],
            'headers' => json_decode((string) $row['headers'], true, 512, JSON_THROW_ON_ERROR),
        ]);

        return [
            $envelope
                ->with(new TransportMessageIdStamp((int) $row['id']))
                ->with(new BrokerPassportStamp(
                    'async',
                    'amqpsim',
                    (string) $row['exchange'],
                    (string) $row['queue'],
                    (string) $row['routing_key'],
                )),
        ];
    }

    public function ack(Envelope $envelope): void
    {
        $id = $envelope->last(TransportMessageIdStamp::class)?->getId();
        if ($id === null) {
            return;
        }
        $stmt = $this->pdo->prepare('DELETE FROM amqp_sim_message WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function reject(Envelope $envelope): void
    {
        $id = $envelope->last(TransportMessageIdStamp::class)?->getId();
        if ($id === null) {
            return;
        }
        $stmt = $this->pdo->prepare(
            'UPDATE amqp_sim_message SET delivered_at = NULL WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
    }

    public function getMessageCount(): int
    {
        $this->setup();
        $stmt = $this->pdo->query(
            'SELECT COUNT(*) FROM amqp_sim_message WHERE queue = '.$this->pdo->quote($this->queue).' AND delivered_at IS NULL'
        );

        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array{vhost: string, exchange: string, queue: string, routing_key: string, pending: int}
     */
    public function topologySnapshot(): array
    {
        return [
            'vhost' => $this->vhost,
            'exchange' => $this->exchange,
            'queue' => $this->queue,
            'routing_key' => $this->routingKey,
            'pending' => $this->getMessageCount(),
        ];
    }

    private function setup(): void
    {
        if ($this->setupDone) {
            return;
        }

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS amqp_sim_message (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                vhost TEXT NOT NULL,
                exchange TEXT NOT NULL,
                queue TEXT NOT NULL,
                routing_key TEXT NOT NULL,
                body TEXT NOT NULL,
                headers TEXT NOT NULL,
                created_at TEXT NOT NULL,
                delivered_at TEXT NULL
            )'
        );
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS amqp_sim_topology (
                vhost TEXT NOT NULL,
                exchange TEXT NOT NULL,
                queue TEXT NOT NULL,
                routing_key TEXT NOT NULL,
                PRIMARY KEY (vhost, exchange, queue, routing_key)
            )'
        );
        $stmt = $this->pdo->prepare(
            'INSERT OR REPLACE INTO amqp_sim_topology (vhost, exchange, queue, routing_key)
             VALUES (:vhost, :exchange, :queue, :rk)'
        );
        $stmt->execute([
            'vhost' => $this->vhost,
            'exchange' => $this->exchange,
            'queue' => $this->queue,
            'rk' => $this->routingKey,
        ]);
        $this->setupDone = true;
    }
}
