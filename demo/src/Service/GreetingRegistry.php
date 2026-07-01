<?php

namespace App\Service;

use App\Contract\GreetingGeneratorInterface;

/**
 * Registr všech greeting generatorů — naplní se až při kompilaci containeru (compiler pass).
 */
final class GreetingRegistry
{
    /**
     * @param iterable<GreetingGeneratorInterface> $generators
     */
    public function __construct(
        private iterable $generators,
    ) {
    }

    /**
     * @return list<string>
     */
    public function getImplementationNames(): array
    {
        $names = [];
        foreach ($this->generators as $generator) {
            $names[] = (new \ReflectionClass($generator))->getShortName();
        }

        return $names;
    }

    /**
     * @return list<string>
     */
    public function greetAll(string $name): array
    {
        $messages = [];
        foreach ($this->generators as $generator) {
            $messages[] = $generator->greet($name);
        }

        return $messages;
    }
}
