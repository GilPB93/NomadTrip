<?php

namespace App\Entity;

use App\Repository\SouvenirsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SouvenirsRepository::class)]
class Souvenirs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['souvenirs:read', 'souvenirs:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['souvenirs:read', 'souvenirs:write'])]
    private ?string $what = null;

    #[ORM\Column(length: 255)]
    #[Groups(['souvenirs:read', 'souvenirs:write'])]
    private ?string $forWho = null;

    #[ORM\ManyToOne(targetEntity: Travelbook::class, cascade: ['persist'], inversedBy: 'souvenirs')]
    #[Groups(['souvenirs:read', 'souvenirs:write'])]
    private ?Travelbook $travelbook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhat(): ?string
    {
        return $this->what;
    }

    public function setWhat(string $what): static
    {
        $this->what = $what;

        return $this;
    }

    public function getForWho(): ?string
    {
        return $this->forWho;
    }

    public function setForWho(string $forWho): static
    {
        $this->forWho = $forWho;

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
