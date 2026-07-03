<?php

namespace App\EventSubscriber;

use App\Entity\StudyTopic;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

/**
 * Observer — reaguje na persist StudyTopic (Doctrine postPersist).
 */
#[AsDoctrineListener(event: Events::postPersist)]
final class StudyTopicSubscriber
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof StudyTopic) {
            return;
        }

        $this->logger->info('StudyTopic created (observer)', [
            'slug' => $entity->getSlug(),
            'day' => $entity->getStudyDay()->getNumber(),
        ]);
    }
}
