<?php

namespace App\Curriculum\Entity;

use App\Curriculum\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $text;

    #[ORM\Column(type: Types::SMALLFLOAT, nullable: true)]
    private ?float $difficultyWeight;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Topic $topic;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\OneToMany(targetEntity: Option::class, mappedBy: 'question')]
    private Collection $options;

    public function __construct(Topic $topic, string $text, ?float $difficultyWeight = null)
    {
        $this->topic = $topic;
        $this->text = $text;
        $this->difficultyWeight = $difficultyWeight;
        $this->createdAt = new \DateTimeImmutable();
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDifficultyWeight(): float
    {
        return $this->difficultyWeight ?? 1.0;
    }

    public function setDifficultyWeight(?float $difficultyWeight): static
    {
        $this->difficultyWeight = $difficultyWeight;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }
}
