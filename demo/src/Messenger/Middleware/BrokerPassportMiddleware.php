<?php

namespace App\Messenger\Middleware;

use App\Messenger\BrokerPassportContext;
use App\Messenger\Stamp\BrokerPassportStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class BrokerPassportMiddleware implements MiddlewareInterface
{
    public function __construct(
        private BrokerPassportContext $context,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->context->set($envelope->last(BrokerPassportStamp::class));
        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            $this->context->set(null);
        }
    }
}
