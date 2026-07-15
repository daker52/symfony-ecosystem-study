<?php

namespace App\Message\Event;

/** Async stage pipeline — jedna zpráva = jeden krok. */
final readonly class AdvanceWorkOrderMessage
{
    public function __construct(
        public int $workOrderId,
    ) {
    }
}
