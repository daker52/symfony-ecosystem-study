<?php

namespace App\Factory;

use App\Contract\GreetingGeneratorInterface;
use App\Service\CasualGreetingGenerator;
use App\Service\Decorator\LoggingGreetingGeneratorDecorator;
use App\Service\FormalGreetingGenerator;
use Psr\Log\LoggerInterface;

/**
 * Factory — vytvoří správnou Strategy (+ volitelně Decorator).
 */
final class GreetingGeneratorFactory
{
    public function __construct(
        private FormalGreetingGenerator $formal,
        private CasualGreetingGenerator $casual,
        private LoggerInterface $logger,
    ) {
    }

    public function create(string $style = 'formal', bool $withLogging = true): GreetingGeneratorInterface
    {
        $inner = match ($style) {
            'casual' => $this->casual,
            default => $this->formal,
        };

        if (!$withLogging) {
            return $inner;
        }

        return new LoggingGreetingGeneratorDecorator($inner, $this->logger);
    }
}
