<?php

namespace App\bit\webspace\account;

use App\bit\webspace\data\Auditable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[Entity(repositoryClass: AccountRepository::class)]
#[Table(name: 'Accounts')]
class Account extends Auditable {
  #[NotNull(groups: ['update']), GreaterThan(0, groups: ['update'])]
  #[Id, GeneratedValue, Column]
  private ?int $id = null;

  #[NotBlank, Email]
  #[Column(length: 255)]
  private string $email;

  #[Column]
  private bool $enabled = false;

  #[Column(length: 255, nullable: true)]
  private ?string $password = null;

  #[Column]
  private array $roles = [];

  #[NotBlank, Length(min: 3, max: 100)]
  #[Column(length: 100, unique: true)]
  private string $username;

  /**
   * @return int|null
   */
  public function getId(): ?int {
    return $this->id;
  }

  /**
   * @param int|null $id
   * @return Account
   */
  public function setId(?int $id): Account {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getEmail(): string {
    return $this->email;
  }

  /**
   * @param string $email
   * @return Account
   */
  public function setEmail(string $email): Account {
    $this->email = $email;
    return $this;
  }

  /**
   * @return bool
   */
  public function isEnabled(): bool {
    return $this->enabled;
  }

  /**
   * @param bool $enabled
   * @return Account
   */
  public function setEnabled(bool $enabled): Account {
    $this->enabled = $enabled;
    return $this;
  }

  /**
   * @return string|null
   */
  public function getPassword(): ?string {
    return $this->password;
  }

  /**
   * @param string|null $password
   * @return Account
   */
  public function setPassword(?string $password): Account {
    $this->password = $password;
    return $this;
  }

  /**
   * @param string $role
   * @return Account
   */
  public function addRole(string $role): self {
    if (!in_array($role, $this->roles)) {
      $this->roles[] = $role;
    }
    return $this;
  }

  /**
   * @return array
   */
  public function getRoles(): array {
    return $this->roles;
  }

  /**
   * @param array $roles
   * @return Account
   */
  public function setRoles(array $roles): Account {
    $this->roles = $roles;
    return $this;
  }

  /**
   * @return string
   */
  public function getUsername(): string {
    return $this->username;
  }

  /**
   * @param string $username
   * @return Account
   */
  public function setUsername(string $username): Account {
    $this->username = $username;
    return $this;
  }
}
