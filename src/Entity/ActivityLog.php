<?php

namespace App\Entity;

use App\Repository\ActivityLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityLogRepository::class)]
class ActivityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastLogin = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $totalConnectionTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastLogin(): ?\DateTimeImmutable
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeImmutable $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getTotalConnectionTime(): ?\DateTimeInterface
    {
        return $this->totalConnectionTime;
    }

    public function setTotalConnectionTime(\DateTimeInterface $totalConnectionTime): static
    {
        $this->totalConnectionTime = $totalConnectionTime;

        return $this;
    }
}
