<?php

namespace App\Entity;

use App\Repository\TravelbookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TravelbookRepository::class)]
class Travelbook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['travelbook:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?\DateTimeImmutable $departureAt = null;

    #[ORM\Column]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?\DateTimeImmutable $comebackAt = null;

    #[ORM\Column(length: 28, nullable: true)]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?string $flightNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?string $accommodation = null;

    #[ORM\Column]
    #[Groups(['travelbook:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['travelbook:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'travelbooks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Places>
     */
    #[ORM\OneToMany(targetEntity: Places::class, mappedBy: 'travelbook')]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private Collection $places;

    /**
     * @var Collection<int, FB>
     */
    #[ORM\OneToMany(targetEntity: FB::class, mappedBy: 'travelbook')]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private Collection $fBs;

    /**
     * @var Collection<int, Souvenirs>
     */
    #[ORM\OneToMany(targetEntity: Souvenirs::class, mappedBy: 'travelbook')]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private Collection $souvenirs;

    /**
     * @var Collection<int, Photos>
     */
    #[ORM\OneToMany(targetEntity: Photos::class, mappedBy: 'travelbook')]
    #[Groups(['travelbook:read', 'travelbook:write'])]
    private Collection $photos;

    #[ORM\Column(length: 255)]
    private ?string $imgCouverture = null;

    public function __construct()
    {
        $this->places = new ArrayCollection();
        $this->fBs = new ArrayCollection();
        $this->souvenirs = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDepartureAt(): ?\DateTimeImmutable
    {
        return $this->departureAt;
    }

    public function setDepartureAt(\DateTimeImmutable $departureAt): static
    {
        $this->departureAt = $departureAt;

        return $this;
    }

    public function getComebackAt(): ?\DateTimeImmutable
    {
        return $this->comebackAt;
    }

    public function setComebackAt(\DateTimeImmutable $comebackAt): static
    {
        $this->comebackAt = $comebackAt;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(?string $flightNumber): static
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getAccommodation(): ?string
    {
        return $this->accommodation;
    }

    public function setAccommodation(?string $accommodation): static
    {
        $this->accommodation = $accommodation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    /**
     * @return Collection<int, Places>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Places $place): static
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
            $place->setTravelbook($this);
        }

        return $this;
    }

    public function removePlace(Places $place): static
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getTravelbook() === $this) {
                $place->setTravelbook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FB>
     */
    public function getFBs(): Collection
    {
        return $this->fBs;
    }

    public function addFB(FB $fB): static
    {
        if (!$this->fBs->contains($fB)) {
            $this->fBs->add($fB);
            $fB->setTravelbook($this);
        }

        return $this;
    }

    public function removeFB(FB $fB): static
    {
        if ($this->fBs->removeElement($fB)) {
            // set the owning side to null (unless already changed)
            if ($fB->getTravelbook() === $this) {
                $fB->setTravelbook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Souvenirs>
     */
    public function getSouvenirs(): Collection
    {
        return $this->souvenirs;
    }

    public function addSouvenir(Souvenirs $souvenir): static
    {
        if (!$this->souvenirs->contains($souvenir)) {
            $this->souvenirs->add($souvenir);
            $souvenir->setTravelbook($this);
        }

        return $this;
    }

    public function removeSouvenir(Souvenirs $souvenir): static
    {
        if ($this->souvenirs->removeElement($souvenir)) {
            // set the owning side to null (unless already changed)
            if ($souvenir->getTravelbook() === $this) {
                $souvenir->setTravelbook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setTravelbook($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTravelbook() === $this) {
                $photo->setTravelbook(null);
            }
        }

        return $this;
    }

    public function getImgCouverture(): ?string
    {
        return $this->imgCouverture;
    }

    public function setImgCouverture(string $imgCouverture): static
    {
        $this->imgCouverture = $imgCouverture;

        return $this;
    }
}