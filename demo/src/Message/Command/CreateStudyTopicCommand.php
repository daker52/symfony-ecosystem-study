<?php

namespace App\Message\Command;

/**
 * CQRS Command — mění stav (zápis).
 */
final readonly class CreateStudyTopicCommand
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $body,
        public int $dayNumber,
    ) {
    }
}
