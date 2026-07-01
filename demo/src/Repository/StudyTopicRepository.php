<?php

namespace App\Repository;

use App\Entity\StudyTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudyTopic>
 */
class StudyTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyTopic::class);
    }

    public function findBySlug(string $slug): ?StudyTopic
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @return list<StudyTopic>
     */
    public function findLatest(int $limit = 20): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
