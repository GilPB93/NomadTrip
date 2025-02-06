<?php

namespace App\Entity;

use App\Repository\PlacesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlacesRepository::class)]
class Places
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['places:read', 'places:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['places:read', 'places:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['places:read', 'places:write'])]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['places:read', 'places:write'])]
    private ?\DateTimeImmutable $visitAt = null;

    #[ORM\ManyToOne(targetEntity: Travelbook::class, cascade: ['persist'], inversedBy: 'places')]
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
