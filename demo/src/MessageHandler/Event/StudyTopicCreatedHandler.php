<?php

namespace App\MessageHandler\Event;

use App\Message\Event\StudyTopicCreatedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class StudyTopicCreatedHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(StudyTopicCreatedMessage $message): void
    {
        $this->logger->info('Async: study topic vytvořen', [
            'slug' => $message->slug,
            'title' => $message->title,
            'day' => $message->dayNumber,
        ]);
    }
}
