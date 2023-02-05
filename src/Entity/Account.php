<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: 'Accounts')]
class Account {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Assert\NotBlank]
  #[Assert\Length(min: 3, max: 100)]
  #[ORM\Column(length: 100, unique: true)]
  private ?string $username = null;

  #[ORM\Column(length: 255)]
  private ?string $email = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $password = null;

  #[ORM\Column]
  private ?bool $enabled = null;

  #[ORM\Column(nullable: true)]
  private array $roles = [];

  public function getId(): ?int {
    return $this->id;
  }

  public function getUsername(): ?string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): ?string {
    return $this->password;
  }

  public function setPassword(?string $password): self {
    $this->password = $password;

    return $this;
  }

  public function isEnabled(): ?bool {
    return $this->enabled;
  }

  public function setEnabled(bool $enabled): self {
    $this->enabled = $enabled;

    return $this;
  }

  public function getRoles(): array {
    return $this->roles;
  }

  public function setRoles(?array $roles): self {
    $this->roles = $roles;

    return $this;
  }

  public function addRole(AccountRole $role): self {
    if (!in_array($role->name, $this->roles)) {
      $this->roles[] = $role->name;
    }
    return $this;
  }
}
