<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'work_order_event')]
class WorkOrderEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private WorkOrder $workOrder;

    #[ORM\Column(length: 40)]
    private string $stage;

    #[ORM\Column(length: 255)]
    private string $message;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $viaBroker = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $viaExchange = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $viaQueue = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $viaRoutingKey = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        WorkOrder $workOrder,
        string $stage,
        string $message,
        ?string $viaBroker = null,
        ?string $viaExchange = null,
        ?string $viaQueue = null,
        ?string $viaRoutingKey = null,
    ) {
        $this->workOrder = $workOrder;
        $this->stage = $stage;
        $this->message = $message;
        $this->viaBroker = $viaBroker;
        $this->viaExchange = $viaExchange;
        $this->viaQueue = $viaQueue;
        $this->viaRoutingKey = $viaRoutingKey;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getViaBroker(): ?string
    {
        return $this->viaBroker;
    }

    public function getViaExchange(): ?string
    {
        return $this->viaExchange;
    }

    public function getViaQueue(): ?string
    {
        return $this->viaQueue;
    }

    public function getViaRoutingKey(): ?string
    {
        return $this->viaRoutingKey;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
