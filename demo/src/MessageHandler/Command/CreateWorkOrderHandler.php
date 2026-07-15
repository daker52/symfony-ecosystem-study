<?php

namespace App\MessageHandler\Command;

use App\Entity\WorkOrder;
use App\Message\Command\CreateWorkOrderCommand;
use App\Message\Event\AdvanceWorkOrderMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(bus: 'command.bus')]
final class CreateWorkOrderHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreateWorkOrderCommand $command): int
    {
        $order = new WorkOrder($command->title, $command->type);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->commandBus->dispatch(new AdvanceWorkOrderMessage((int) $order->getId()));

        return (int) $order->getId();
    }
}
