<?php

namespace App\Service;

/**
 * Export topology do RabbitMQ definitions.json — importovatelné v Management UI.
 * Nejdřív navrhneš topologii v kódu (DSN), pak ji „vypálíš“ do brokera.
 */
final class RabbitTopologyExporter
{
    /**
     * @param array{exchange: string, queue: string, routing_key: string, vhost?: string} $topology
     *
     * @return array<string, mixed>
     */
    public function toDefinitions(array $topology): array
    {
        $vhost = $topology['vhost'] ?? '/';
        $exchange = $topology['exchange'];
        $queue = $topology['queue'];
        $routingKey = $topology['routing_key'];

        return [
            'rabbit_version' => '3.13.0',
            'rabbitmq_version' => '3.13.0',
            'product_name' => 'pulse-topology-export',
            'product_version' => 'den-15',
            'users' => [],
            'vhosts' => [
                ['name' => $vhost],
            ],
            'permissions' => [],
            'topic_permissions' => [],
            'parameters' => [],
            'global_parameters' => [],
            'policies' => [],
            'queues' => [
                [
                    'name' => $queue,
                    'vhost' => $vhost,
                    'durable' => true,
                    'auto_delete' => false,
                    'arguments' => new \stdClass(),
                ],
            ],
            'exchanges' => [
                [
                    'name' => $exchange,
                    'vhost' => $vhost,
                    'type' => 'direct',
                    'durable' => true,
                    'auto_delete' => false,
                    'internal' => false,
                    'arguments' => new \stdClass(),
                ],
            ],
            'bindings' => [
                [
                    'source' => $exchange,
                    'vhost' => $vhost,
                    'destination' => $queue,
                    'destination_type' => 'queue',
                    'routing_key' => $routingKey,
                    'arguments' => new \stdClass(),
                ],
            ],
        ];
    }

    /**
     * @param array{exchange: string, queue: string, routing_key: string, vhost?: string} $topology
     */
    public function writeJson(array $topology, string $path): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents(
            $path,
            json_encode($this->toDefinitions($topology), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
        );
    }
}
