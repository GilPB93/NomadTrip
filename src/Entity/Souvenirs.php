<?php

namespace App\Entity;

use App\Repository\SouvenirsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SouvenirsRepository::class)]
class Souvenirs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $what = null;

    #[ORM\Column(length: 255)]
    private ?string $forWho = null;

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
}
