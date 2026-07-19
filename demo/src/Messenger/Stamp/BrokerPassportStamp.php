<?php

namespace App\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * Den 15 — „Broker Passport“: kde zpráva reálně letěla (exchange → queue).
 * Sedí na amqpsim i na živém Rabbit (amqplib).
 */
final class BrokerPassportStamp implements StampInterface
{
    public function __construct(
        public string $transportAlias,
        public string $brokerKind,
        public string $exchange,
        public string $queue,
        public string $routingKey,
    ) {
    }

    public function label(): string
    {
        return sprintf(
            '%s · %s → %s (rk=%s)',
            $this->brokerKind,
            $this->exchange,
            $this->queue,
            $this->routingKey,
        );
    }
}
