<?php

namespace App\MessageHandler\Event;

use App\Entity\WorkOrder;
use App\Message\Event\AdvanceWorkOrderMessage;
use App\Repository\WorkOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class AdvanceWorkOrderHandler
{
    public function __construct(
        private WorkOrderRepository $workOrderRepository,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $commandBus,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(AdvanceWorkOrderMessage $message): void
    {
        $order = $this->workOrderRepository->find($message->workOrderId);
        if (!$order instanceof WorkOrder) {
            return;
        }

        if (in_array($order->getStatus(), [WorkOrder::STATUS_DONE, WorkOrder::STATUS_FAILED], true)) {
            return;
        }

        // malá pauza — na dashboardu je vidět průběh stage po stage
        usleep(400_000);

        $next = $order->nextStageAfter($order->getCurrentStage());
        if ($next === null || $next === 'complete') {
            $order->markDone();
            $this->entityManager->flush();
            $this->logger->info('Pulse work order done', ['id' => $order->getId()]);

            return;
        }

        $labels = [
            'validate' => 'Validace payloadu a typu jobu',
            'process' => 'Zpracování doménové logiky',
            'notify' => 'Async notifikace (side-effect)',
            'complete' => 'Finalizace',
        ];

        $order->startStage($next, $labels[$next] ?? $next);
        $this->entityManager->flush();

        // další krok znovu do fronty — řetěz zpráv
        $this->commandBus->dispatch(new AdvanceWorkOrderMessage((int) $order->getId()));
    }
}
