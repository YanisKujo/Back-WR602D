<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ApiResource]
class Game
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $playedAt;

    #[ORM\Column]
    private int $betAmount;

    #[ORM\Column(length: 10)]
    private string $betOn; // Exemple : 'red', 'black', 'green'

    #[ORM\Column]
    private bool $hasWon;

    #[ORM\Column]
    private int $gain;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: 'uuid')]
    private ?User $user = null;


    public function __construct()
    {
        $this->playedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPlayedAt(): \DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeImmutable $playedAt): self
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    public function getBetAmount(): int
    {
        return $this->betAmount;
    }

    public function setBetAmount(int $betAmount): self
    {
        $this->betAmount = $betAmount;

        return $this;
    }

    public function getBetOn(): string
    {
        return $this->betOn;
    }

    public function setBetOn(string $betOn): self
    {
        $this->betOn = $betOn;

        return $this;
    }

    public function getHasWon(): bool
    {
        return $this->hasWon;
    }

    public function setHasWon(bool $hasWon): self
    {
        $this->hasWon = $hasWon;

        return $this;
    }

    public function getGain(): int
    {
        return $this->gain;
    }

    public function setGain(int $gain): self
    {
        $this->gain = $gain;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    #[ORM\Column]
    private int $number;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }
}