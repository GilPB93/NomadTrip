<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Enum\AccountStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Random\RandomException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:read'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 128)]
    #[Groups(['user:read'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 128)]
    #[Groups(['user:read'])]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'account_status', length: 20)]
    #[Groups(['user:read'])]
    private ?AccountStatus $accountStatus = null;

    #[ORM\Column(length: 255)]
    private ?string $apiToken = null;

    /**
     * @var Collection<int, Travelbook>
     */
    #[ORM\OneToMany(targetEntity: Travelbook::class, mappedBy: 'user')]
    #[Groups(['user:travelbooks'])]
    private Collection $travelbooks;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastLogin = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['user:read'])]
    private int $connectionTime = 0;

    /** @throws RandomException */
    public function __construct()
    {
        $this->apiToken = bin2hex(random_bytes(32));
        $this->travelbooks = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getAccountStatus(): ?AccountStatus
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(AccountStatus $accountStatus): static
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection<int, Travelbook>
     */
    public function getTravelbooks(): Collection
    {
        return $this->travelbooks;
    }

    public function addTravelbook(Travelbook $travelbook): static
    {
        if (!$this->travelbooks->contains($travelbook)) {
            $this->travelbooks->add($travelbook);
            $travelbook->setUser($this);
        }

        return $this;
    }

    public function removeTravelbook(Travelbook $travelbook): static
    {
        if ($this->travelbooks->removeElement($travelbook)) {
            // set the owning side to null (unless already changed)
            if ($travelbook->getUser() === $this) {
                $travelbook->setUser(null);
            }
        }

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

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function updateLastLogin(): void
    {
        $this->lastLogin = new \DateTimeImmutable();
    }

    public function getConnectionTime(): int
    {
        return $this->connectionTime;
    }

    public function addConnectionTime(int $seconds): static
    {
        $this->connectionTime += $seconds;
        return $this;
    }
    public function addConnectionDuration(int $seconds): void
    {
        $this->connectionTime += $seconds;
    }

}
