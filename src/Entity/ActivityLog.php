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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $login = null;

    #[ORM\Column(nullable: true)]
    private ?int $durationOfConnection = null;

    #[ORM\ManyToOne(inversedBy: 'activityLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $logout = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?\DateTimeInterface
    {
        return $this->login;
    }

    public function setLogin(\DateTimeInterface $login): static
    {
        $this->login = $login;

        return $this;
    }


    public function getDurationOfConnection(): ?int
    {
        return $this->durationOfConnection;
    }

    public function setDurationOfConnection(int $durationOfConnection): static
    {
        $this->durationOfConnection = $durationOfConnection;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLogout(): ?\DateTimeInterface
    {
        return $this->logout;
    }

    public function setLogout(\DateTimeInterface $logout): static
    {
        $this->logout = $logout;

        return $this;
    }
}
