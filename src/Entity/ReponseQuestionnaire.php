<?php

namespace App\Entity;

use App\Repository\ReponseQuestionnaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // 1. Import Constraints

#[ORM\Entity(repositoryClass: ReponseQuestionnaireRepository::class)]
#[ORM\Table(name: 'reponse_questionnaire')]
class ReponseQuestionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'id_agent', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Agent $agent = null;

    #[ORM\ManyToOne(targetEntity: QuestionnaireAgent::class)]
    #[ORM\JoinColumn(name: 'questionnaire_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?QuestionnaireAgent $questionnaire = null;

    // ✅ QUESTION 1: Mandatory + Min 5 chars + No Bad Words
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "You must answer this question.")]
    #[Assert\Length(
        min: 5, 
        minMessage: "Your answer is too short. Please write at least {{ limit }} characters."
    )]
    #[Assert\Regex(
        pattern: '/(badword|insult|stupid)/i', 
        match: false, 
        message: "Please be professional. Profanity is not allowed."
    )]
    private ?string $rep1 = null;

    // ✅ QUESTION 2: Mandatory + Min 5 chars
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "You must answer this question.")]
    #[Assert\Length(
        min: 5, 
        minMessage: "Your answer is too short. Please write at least {{ limit }} characters."
    )]
    private ?string $rep2 = null;

    // ✅ QUESTION 3: Optional in DB, but if filled, must be > 5 chars
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5, 
        minMessage: "Your answer is too short. Please write at least {{ limit }} characters."
    )]
    private ?string $rep3 = null;

    // ✅ QUESTION 4: Optional in DB, but if filled, must be > 5 chars
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 5, 
        minMessage: "Your answer is too short. Please write at least {{ limit }} characters."
    )]
    private ?string $rep4 = null;

    // ... Getters and Setters remain the same ...
    public function getId(): ?int { return $this->id; }

    public function getAgent(): ?Agent { return $this->agent; }
    public function setAgent(Agent $agent): self { $this->agent = $agent; return $this; }

    public function getQuestionnaire(): ?QuestionnaireAgent { return $this->questionnaire; }
    public function setQuestionnaire(QuestionnaireAgent $q): self { $this->questionnaire = $q; return $this; }

    public function getRep1(): ?string { return $this->rep1; }
    public function setRep1(?string $rep1): self { $this->rep1 = $rep1; return $this; }

    public function getRep2(): ?string { return $this->rep2; }
    public function setRep2(?string $rep2): self { $this->rep2 = $rep2; return $this; }

    public function getRep3(): ?string { return $this->rep3; }
    public function setRep3(?string $rep3): self { $this->rep3 = $rep3; return $this; }

    public function getRep4(): ?string { return $this->rep4; }
    public function setRep4(?string $rep4): self { $this->rep4 = $rep4; return $this; }
}