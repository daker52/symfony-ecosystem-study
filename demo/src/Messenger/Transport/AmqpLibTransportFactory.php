<?php

namespace App\Messenger\Transport;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * @implements TransportFactoryInterface<AmqpLibTransport>
 */
final class AmqpLibTransportFactory implements TransportFactoryInterface
{
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        $query = [];
        $parsed = parse_url($dsn);
        if (!is_array($parsed)) {
            throw new \InvalidArgumentException(sprintf('Invalid amqplib DSN: %s', $dsn));
        }
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        $user = urldecode((string) ($parsed['user'] ?? 'guest'));
        $pass = urldecode((string) ($parsed['pass'] ?? 'guest'));
        $host = (string) ($parsed['host'] ?? '127.0.0.1');
        $port = (int) ($parsed['port'] ?? 5672);
        $vhost = isset($parsed['path']) ? rawurldecode(ltrim($parsed['path'], '/')) : '/';
        if ($vhost === '' || $vhost === '%2f') {
            $vhost = '/';
        }

        $exchange = (string) ($query['exchange'] ?? 'pulse.work');
        $queue = (string) ($query['queue'] ?? 'pulse.async');
        $routingKey = (string) ($query['routing_key'] ?? 'work.advance');

        return new AmqpLibTransport(
            $serializer,
            $host,
            $port,
            $user,
            $pass,
            $vhost,
            $exchange,
            $queue,
            $routingKey,
        );
    }

    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'amqplib://');
    }
}
