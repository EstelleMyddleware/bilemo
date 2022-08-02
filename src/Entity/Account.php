<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ApiResource(
    collectionOperations: [],
    itemOperations: ['get'],
    attributes: [
        'pagination_items_per_page' => 10,
        'formats' => ['json']
    ]
)]
#[UniqueEntity(fields: ['name'])]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int  $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['user:read'])]
    private ?string $industry;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:read'])]
    private ?string $primaryEmail;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'change', field: ['name', 'description', 'industry', 'primaryEmail', 'users'])]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: User::class, orphanRemoval: true)]
    #[ApiSubresource]
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIndustry(): ?string
    {
        return $this->industry;
    }

    public function setIndustry(?string $industry): self
    {
        $this->industry = $industry;

        return $this;
    }

    public function getPrimaryEmail(): ?string
    {
        return $this->primaryEmail;
    }

    public function setPrimaryEmail(string $primaryEmail): self
    {
        $this->primaryEmail = $primaryEmail;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAccount($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAccount() === $this) {
                $user->setAccount(null);
            }
        }

        return $this;
    }
}
