<?php

namespace App\MessageHandler\Command;

use App\Domain\ValueObject\TopicSlug;
use App\Entity\StudyDay;
use App\Entity\StudyTopic;
use App\Message\Command\CreateStudyTopicCommand;
use App\Message\Event\StudyTopicCreatedMessage;
use App\Repository\StudyDayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(bus: 'command.bus')]
final class CreateStudyTopicHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StudyDayRepository $dayRepository,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreateStudyTopicCommand $command): string
    {
        $slug = TopicSlug::fromString($command->slug);

        $day = $this->dayRepository->findByNumber($command->dayNumber)
            ?? new StudyDay($command->dayNumber, sprintf('Den %d', $command->dayNumber));

        if ($day->getId() === null) {
            $this->entityManager->persist($day);
        }

        $topic = new StudyTopic($command->title, $slug->toString(), $command->body, $day);
        $this->entityManager->persist($topic);
        $this->entityManager->flush();

        $this->commandBus->dispatch(new StudyTopicCreatedMessage(
            $slug->toString(),
            $command->title,
            $command->dayNumber,
        ));

        return $slug->toString();
    }
}
