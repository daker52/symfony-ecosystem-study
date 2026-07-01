<?php

namespace App\Entity;

use App\Repository\StudyTopicRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudyTopicRepository::class)]
class StudyTopic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 160)]
    private string $title;

    #[ORM\Column(length: 180, unique: true)]
    private string $slug;

    #[ORM\Column(type: Types::TEXT)]
    private string $body;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    #[ORM\JoinColumn(nullable: false)]
    private StudyDay $studyDay;

    public function __construct(string $title, string $slug, string $body, StudyDay $studyDay)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->body = $body;
        $this->studyDay = $studyDay;
        $this->createdAt = new \DateTimeImmutable();
        $studyDay->addTopic($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStudyDay(): StudyDay
    {
        return $this->studyDay;
    }

    public function setStudyDay(StudyDay $studyDay): static
    {
        $this->studyDay = $studyDay;

        return $this;
    }
}
