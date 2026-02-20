<?php

namespace App\Entity;

use App\Repository\QuestionnaireAgentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Agent;


#[ORM\Entity(repositoryClass: QuestionnaireAgentRepository::class)]
#[UniqueEntity(fields: ['game'], message: 'This game already has a questionnaire.')]
class QuestionnaireAgent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'id_agent', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Agent $agent = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le jeu est obligatoire.")]
    private ?string $game = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/\?$/', message: 'The question must end with a "?"')]
    private ?string $ques1 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/\?$/', message: 'The question must end with a "?"')]
    private ?string $ques2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/\?$/', message: 'The question must end with a "?"')]
    private ?string $ques3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/\?$/', message: 'The question must end with a "?"')]
    private ?string $ques4 = null;

    public function getId(): ?int { return $this->id; }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;
        return $this;
    }

     public function getGame(): ?string
    {
        return $this->game;
    }

    public function setGame(?string $game): static
    {
        $this->game = $game;
        return $this;
    }

    public function getQues1(): ?string { return $this->ques1; }
    public function setQues1(string $ques1): static { $this->ques1 = $ques1; return $this; }

    public function getQues2(): ?string { return $this->ques2; }
    public function setQues2(string $ques2): static { $this->ques2 = $ques2; return $this; }

    public function getQues3(): ?string { return $this->ques3; }
    public function setQues3(?string $ques3): static { $this->ques3 = $ques3; return $this; }

    public function getQues4(): ?string { return $this->ques4; }
    public function setQues4(?string $ques4): static { $this->ques4 = $ques4; return $this; }
}
