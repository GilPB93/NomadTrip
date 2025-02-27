<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
#[Vich\Uploadable]
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

    #[Vich\UploadableField(mapping: "travelbook_photos", fileNameProperty: "imgUrl")]
    #[Assert\Image()]
    private ?File $imgUrlFile = null;

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
        return $this->imgUrl ? '/uploads/photos/' . $this->imgUrl : null;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    public function setImgUrlFile(?File $file = null): self
    {
        $this->imgUrlFile = $file;

        if ($file) {
            $this->addedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getImgUrlFile(): ?File
    {
        return $this->imgUrlFile;
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
