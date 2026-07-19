<?php

namespace App\Messenger\Transport;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * @implements TransportFactoryInterface<AmqpSimTransport>
 */
final class AmqpSimTransportFactory implements TransportFactoryInterface
{
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        $query = [];
        $parsed = parse_url($dsn);
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        $exchange = (string) ($options['exchange']['name'] ?? $query['exchange'] ?? 'pulse.work');
        $queue = (string) ($options['queues'][0]['name'] ?? $query['queue'] ?? 'pulse.async');
        $routingKey = (string) ($options['exchange']['default_publish_routing_key'] ?? $query['routing_key'] ?? 'work.advance');
        $path = isset($parsed['path']) ? trim($parsed['path'], '/') : 'default';

        return new AmqpSimTransport(
            $serializer,
            $path !== '' ? $path : 'default',
            $exchange,
            $queue,
            $routingKey,
        );
    }

    public function supports(string $dsn, array $options): bool
    {
        return str_starts_with($dsn, 'amqpsim://');
    }
}
