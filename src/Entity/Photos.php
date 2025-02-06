<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['photos:read', 'photos:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['photos:read', 'photos:write'])]
    private ?string $imgUrl = null;

    #[ORM\Column]
    #[Groups(['photos:read', 'photos:write'])]
    private ?\DateTimeImmutable $addedAt = null;

    #[ORM\ManyToOne(targetEntity: Travelbook::class, cascade: ['persist'], inversedBy: 'photos')]
    #[Groups(['photos:read', 'photos:write'])]
    private ?Travelbook $travelbook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;

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
