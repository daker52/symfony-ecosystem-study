<?php

namespace App\Contract;

interface GreetingGeneratorInterface
{
    public function greet(string $name): string;
}
