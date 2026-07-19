<?php

namespace App\Service;

use App\Entity\WorkOrder;
use App\Entity\WorkOrderEvent;

final class WorkOrderSerializer
{
    /**
     * @return array{
     *     id: int|null,
     *     title: string,
     *     type: string,
     *     status: string,
     *     currentStage: string|null,
     *     createdAt: string,
     *     finishedAt: string|null,
     *     events: list<array{
     *         stage: string,
     *         message: string,
     *         at: string,
     *         viaBroker: string|null,
     *         viaExchange: string|null,
     *         viaQueue: string|null,
     *         viaRoutingKey: string|null
     *     }>
     * }
     */
    public function toArray(WorkOrder $order): array
    {
        return [
            'id' => $order->getId(),
            'title' => $order->getTitle(),
            'type' => $order->getType(),
            'status' => $order->getStatus(),
            'currentStage' => $order->getCurrentStage(),
            'createdAt' => $order->getCreatedAt()->format(\DateTimeInterface::ATOM),
            'finishedAt' => $order->getFinishedAt()?->format(\DateTimeInterface::ATOM),
            'events' => array_map(
                static fn (WorkOrderEvent $event): array => [
                    'stage' => $event->getStage(),
                    'message' => $event->getMessage(),
                    'at' => $event->getCreatedAt()->format(\DateTimeInterface::ATOM),
                    'viaBroker' => $event->getViaBroker(),
                    'viaExchange' => $event->getViaExchange(),
                    'viaQueue' => $event->getViaQueue(),
                    'viaRoutingKey' => $event->getViaRoutingKey(),
                ],
                $order->getEvents()->toArray(),
            ),
        ];
    }
}
