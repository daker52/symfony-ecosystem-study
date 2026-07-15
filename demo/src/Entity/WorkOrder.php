<?php

namespace App\Entity;

use App\Repository\WorkOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkOrderRepository::class)]
#[ORM\Table(name: 'work_order')]
class WorkOrder
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_RUNNING = 'running';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    /** @var list<string> */
    public const STAGES = ['validate', 'process', 'notify', 'complete'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 160)]
    private string $title;

    #[ORM\Column(length: 40)]
    private string $type;

    #[ORM\Column(length: 20)]
    private string $status;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $currentStage = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    /** @var Collection<int, WorkOrderEvent> */
    #[ORM\OneToMany(targetEntity: WorkOrderEvent::class, mappedBy: 'workOrder', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['createdAt' => 'ASC'])]
    private Collection $events;

    public function __construct(string $title, string $type)
    {
        $this->title = $title;
        $this->type = $type;
        $this->status = self::STATUS_QUEUED;
        $this->createdAt = new \DateTimeImmutable();
        $this->events = new ArrayCollection();
        $this->addEvent('queued', 'Work order přijat — čeká ve frontě Messenger.');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCurrentStage(): ?string
    {
        return $this->currentStage;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    /** @return Collection<int, WorkOrderEvent> */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(string $stage, string $message): void
    {
        $this->events->add(new WorkOrderEvent($this, $stage, $message));
    }

    public function startStage(string $stage, string $message): void
    {
        $this->status = self::STATUS_RUNNING;
        $this->currentStage = $stage;
        $this->addEvent($stage, $message);
    }

    public function markDone(): void
    {
        $this->status = self::STATUS_DONE;
        $this->currentStage = 'complete';
        $this->finishedAt = new \DateTimeImmutable();
        $this->addEvent('complete', 'Pipeline hotová.');
    }

    public function markFailed(string $reason): void
    {
        $this->status = self::STATUS_FAILED;
        $this->finishedAt = new \DateTimeImmutable();
        $this->addEvent('failed', $reason);
    }

    public function nextStageAfter(?string $current): ?string
    {
        if ($current === null) {
            return self::STAGES[0];
        }

        $index = array_search($current, self::STAGES, true);
        if ($index === false) {
            return null;
        }

        return self::STAGES[$index + 1] ?? null;
    }
}
