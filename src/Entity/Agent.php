<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le pseudo est obligatoire.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "Le pseudo doit contenir au moins {{ limit }} caractères.", maxMessage: "Le pseudo ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $pseudo = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_player', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotNull(message: "Le joueur est obligatoire.")]
    private ?User $player = null;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(name: 'id_team', referencedColumnName: 'id', nullable: true)]
    private ?Team $team = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le jeu est obligatoire.")]
    private ?string $game = null;

    // ✅ fixed: camelCase property name matches getters/setters
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfCreation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le rang est obligatoire.")]
    private ?string $rank = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut est obligatoire.")]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le lien social est obligatoire.")]
    #[Assert\Url(message: "Le lien social doit être une URL valide.")]
    private ?string $socialsLink = null;

    public function __construct()
    {
        $this->dateOfCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;
        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getGame(): ?string
    {
        return $this->game;
    }

    public function setGame(?string $game): self
    {
        $this->game = $game;
        return $this;
    }
    public function getDateOfCreation(): ?\DateTimeInterface
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeInterface $dateOfCreation): static
    {
        $this->dateOfCreation = $dateOfCreation;
        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(?string $rank): static
    {
        $this->rank = $rank;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getSocialsLink(): ?string
    {
        return $this->socialsLink;
    }

    public function setSocialsLink(?string $socialsLink): static
    {
        $this->socialsLink = $socialsLink;
        return $this;
    }
}
