<?php

namespace App\Service;

use App\Contract\GreetingGeneratorInterface;

final class FormalGreetingGenerator implements GreetingGeneratorInterface
{
    public function greet(string $name): string
    {
        return sprintf('Dobrý den, %s.', $name);
    }
}
