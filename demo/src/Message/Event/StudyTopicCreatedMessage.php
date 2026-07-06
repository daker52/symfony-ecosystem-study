<?php

namespace App\Message\Event;

/**
 * Side-effect po vytvoření topicu — zpracuje se asynchronně (den 7).
 */
final readonly class StudyTopicCreatedMessage
{
    public function __construct(
        public string $slug,
        public string $title,
        public int $dayNumber,
    ) {
    }
}
