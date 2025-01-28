<?php

namespace App\Entity;

use App\Repository\FBRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FBRepository::class)]
class FB
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $visitAt = null;

    #[ORM\ManyToOne(inversedBy: 'fBs')]
    private ?Travelbook $travelbook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getVisitAt(): ?\DateTimeImmutable
    {
        return $this->visitAt;
    }

    public function setVisitAt(?\DateTimeImmutable $visitAt): static
    {
        $this->visitAt = $visitAt;

        return $this;
    }

    public function getTravelbook(): ?Travelbook
    {
        return $this->travelbook;
    }

    public function setTravelbook(?Travelbook $travelbook): static
    {
        $this->travelbook = $travelbook;

        return $this;
    }
}
