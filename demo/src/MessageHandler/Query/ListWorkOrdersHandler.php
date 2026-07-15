<?php

namespace App\MessageHandler\Query;

use App\Entity\WorkOrder;
use App\Message\Query\ListWorkOrdersQuery;
use App\Repository\WorkOrderRepository;
use App\Service\WorkOrderSerializer;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class ListWorkOrdersHandler
{
    public function __construct(
        private WorkOrderRepository $workOrderRepository,
        private WorkOrderSerializer $serializer,
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function __invoke(ListWorkOrdersQuery $query): array
    {
        return array_map(
            fn (WorkOrder $order): array => $this->serializer->toArray($order),
            $this->workOrderRepository->findLatest(40),
        );
    }
}
