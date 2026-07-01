<?php

namespace App\Service;

use App\Contract\GreetingGeneratorInterface;

final class CasualGreetingGenerator implements GreetingGeneratorInterface
{
    public function greet(string $name): string
    {
        return sprintf('Ahoj %s!', $name);
    }
}
