<?php

namespace App\Messenger\Transport;

use App\Messenger\Stamp\BrokerPassportStamp;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Pure PHP AMQP (php-amqplib) → živý RabbitMQ. Bez pecl ext-amqp.
 */
final class AmqpLibTransport implements TransportInterface, MessageCountAwareInterface
{
    private ?AMQPStreamConnection $connection = null;
    /** @var \PhpAmqpLib\Channel\AMQPChannel|null */
    private $channel = null;
    private bool $declared = false;

    public function __construct(
        private SerializerInterface $serializer,
        private string $host,
        private int $port,
        private string $user,
        private string $password,
        private string $vhost,
        private string $exchange,
        private string $queue,
        private string $routingKey,
    ) {
    }

    public function send(Envelope $envelope): Envelope
    {
        $this->declareTopology();
        $encoded = $this->serializer->encode($envelope);
        $headers = new AMQPTable($encoded['headers'] ?? []);
        $msg = new AMQPMessage($encoded['body'], [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            'application_headers' => $headers,
        ]);
        $this->channel()->basic_publish($msg, $this->exchange, $this->routingKey);

        return $envelope->with(new BrokerPassportStamp(
            'async',
            'rabbitmq',
            $this->exchange,
            $this->queue,
            $this->routingKey,
        ));
    }

    public function get(): iterable
    {
        $this->declareTopology();
        $message = $this->channel()->basic_get($this->queue, false);
        if ($message === null) {
            return [];
        }

        /** @var array<string, mixed> $headers */
        $headers = [];
        $appHeaders = $message->get('application_headers');
        if ($appHeaders instanceof AMQPTable) {
            $headers = $appHeaders->getNativeData();
        }

        $envelope = $this->serializer->decode([
            'body' => $message->getBody(),
            'headers' => $headers,
        ]);

        $deliveryTag = (string) $message->getDeliveryTag();

        return [
            $envelope
                ->with(new TransportMessageIdStamp($deliveryTag))
                ->with(new BrokerPassportStamp(
                    'async',
                    'rabbitmq',
                    $this->exchange,
                    $this->queue,
                    $this->routingKey,
                )),
        ];
    }

    public function ack(Envelope $envelope): void
    {
        $id = $envelope->last(TransportMessageIdStamp::class)?->getId();
        if ($id === null) {
            return;
        }
        $this->channel()->basic_ack((int) $id);
    }

    public function reject(Envelope $envelope): void
    {
        $id = $envelope->last(TransportMessageIdStamp::class)?->getId();
        if ($id === null) {
            return;
        }
        $this->channel()->basic_nack((int) $id, false, true);
    }

    public function getMessageCount(): int
    {
        try {
            $this->declareTopology();
            [, $messageCount] = $this->channel()->queue_declare($this->queue, true);

            return (int) $messageCount;
        } catch (\Throwable) {
            return 0;
        }
    }

    /**
     * @return array{host: string, vhost: string, exchange: string, queue: string, routing_key: string, pending: int}
     */
    public function topologySnapshot(): array
    {
        return [
            'host' => $this->host.':'.$this->port,
            'vhost' => $this->vhost,
            'exchange' => $this->exchange,
            'queue' => $this->queue,
            'routing_key' => $this->routingKey,
            'pending' => $this->getMessageCount(),
        ];
    }

    private function declareTopology(): void
    {
        if ($this->declared) {
            return;
        }
        $ch = $this->channel();
        $ch->exchange_declare($this->exchange, 'direct', false, true, false);
        $ch->queue_declare($this->queue, false, true, false, false);
        $ch->queue_bind($this->queue, $this->exchange, $this->routingKey);
        $this->declared = true;
    }

    /** @return \PhpAmqpLib\Channel\AMQPChannel */
    private function channel()
    {
        if ($this->channel !== null && $this->connection?->isConnected()) {
            return $this->channel;
        }

        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
                $this->vhost,
            );
        } catch (\Throwable $e) {
            throw new TransportException(
                'RabbitMQ nedostupný ('.$e->getMessage().'). Spusť Docker compose nebo použij amqpsim:// DSN.',
                0,
                $e,
            );
        }
        $this->channel = $this->connection->channel();

        return $this->channel;
    }

    public function __destruct()
    {
        try {
            $this->channel?->close();
            $this->connection?->close();
        } catch (\Throwable) {
        }
    }
}
