<?php

namespace App\Repository;

use App\Entity\WorkOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkOrder>
 */
class WorkOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkOrder::class);
    }

    /**
     * @return list<WorkOrder>
     */
    public function findLatest(int $limit = 30): array
    {
        return $this->createQueryBuilder('w')
            ->orderBy('w.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array{queued: int, running: int, done: int, failed: int, total: int}
     */
    public function countByStatus(): array
    {
        $rows = $this->createQueryBuilder('w')
            ->select('w.status AS status, COUNT(w.id) AS cnt')
            ->groupBy('w.status')
            ->getQuery()
            ->getArrayResult();

        $stats = [
            'queued' => 0,
            'running' => 0,
            'done' => 0,
            'failed' => 0,
            'total' => 0,
        ];

        foreach ($rows as $row) {
            $status = (string) $row['status'];
            $count = (int) $row['cnt'];
            if (isset($stats[$status])) {
                $stats[$status] = $count;
            }
            $stats['total'] += $count;
        }

        return $stats;
    }
}
