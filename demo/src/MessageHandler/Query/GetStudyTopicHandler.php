<?php

namespace App\MessageHandler\Query;

use App\Domain\ValueObject\TopicSlug;
use App\Message\Query\GetStudyTopicQuery;
use App\Repository\StudyTopicRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler(bus: 'query.bus')]
final class GetStudyTopicHandler
{
    public function __construct(
        private StudyTopicRepository $topicRepository,
    ) {
    }

    /**
     * @return array{slug: string, title: string, body: string, day: int}
     */
    public function __invoke(GetStudyTopicQuery $query): array
    {
        $slug = TopicSlug::fromString($query->slug);
        $topic = $this->topicRepository->findBySlug($slug->toString());

        if ($topic === null) {
            throw new UnrecoverableMessageHandlingException(sprintf('Topic "%s" nenalezen.', $slug));
        }

        return [
            'slug' => $topic->getSlug(),
            'title' => $topic->getTitle(),
            'body' => $topic->getBody(),
            'day' => $topic->getStudyDay()->getNumber(),
        ];
    }
}
