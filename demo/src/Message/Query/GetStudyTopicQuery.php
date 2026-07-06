<?php

namespace App\Message\Query;

/**
 * CQRS Query — jen čte data.
 */
final readonly class GetStudyTopicQuery
{
    public function __construct(
        public string $slug,
    ) {
    }
}
