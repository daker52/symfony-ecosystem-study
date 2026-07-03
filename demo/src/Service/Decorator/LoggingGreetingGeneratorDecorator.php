<?php

namespace App\Service\Decorator;

use App\Contract\GreetingGeneratorInterface;
use Psr\Log\LoggerInterface;

/**
 * Decorator — obalí GreetingGenerator a přidá logování.
 */
final class LoggingGreetingGeneratorDecorator implements GreetingGeneratorInterface
{
    public function __construct(
        private GreetingGeneratorInterface $inner,
        private LoggerInterface $logger,
    ) {
    }

    public function greet(string $name): string
    {
        $message = $this->inner->greet($name);
        $this->logger->info('Greeting generated', [
            'name' => $name,
            'message' => $message,
            'generator' => (new \ReflectionClass($this->inner))->getShortName(),
        ]);

        return $message;
    }
}
