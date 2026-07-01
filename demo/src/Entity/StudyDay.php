<?php

namespace App\Entity;

use App\Repository\StudyDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudyDayRepository::class)]
class StudyDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $number;

    #[ORM\Column(length: 120)]
    private string $title;

    /** @var Collection<int, StudyTopic> */
    #[ORM\OneToMany(targetEntity: StudyTopic::class, mappedBy: 'studyDay', cascade: ['persist'])]
    private Collection $topics;

    public function __construct(int $number, string $title)
    {
        $this->number = $number;
        $this->title = $title;
        $this->topics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /** @return Collection<int, StudyTopic> */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(StudyTopic $topic): static
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setStudyDay($this);
        }

        return $this;
    }
}
