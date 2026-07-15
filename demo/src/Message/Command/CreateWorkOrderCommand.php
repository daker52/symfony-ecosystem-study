<?php

namespace App\Message\Command;

/** CQRS Command — založí Work Order (zápis). */
final readonly class CreateWorkOrderCommand
{
    public function __construct(
        public string $title,
        public string $type,
    ) {
    }
}
