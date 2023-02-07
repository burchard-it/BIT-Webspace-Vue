<?php

namespace App\bit\webspace\data;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Version;
use Symfony\Component\Validator\Constraints\NotNull;

#[MappedSuperclass, HasLifecycleCallbacks]
abstract class Auditable {

  #[Column(type: 'datetime')]
  private DateTime $created;

  #[Column(type: 'datetime', nullable: true)]
  private ?DateTime $modified = null;

  #[NotNull(groups: ['update'])]
  #[Version, Column(type: 'integer', options: ['default' => 0])]
  private int $vnr;

  #[PrePersist]
  public function _prePersist(): void {
    $this->created = new DateTime();
  }

  #[PreUpdate]
  public function _preUpdate(): void {
    $this->modified = new DateTime();
  }

  /**
   * @return DateTime
   */
  public function getCreated(): DateTime {
    return $this->created;
  }

  /**
   * @return int
   */
  public function getVnr(): int {
    return $this->vnr;
  }

  /**
   * @param int $vnr
   * @return Auditable
   */
  public function setVnr(int $vnr): Auditable {
    $this->vnr = $vnr;
    return $this;
  }

  /**
   * @return DateTime
   */
  public function getModified(): DateTime {
    return $this->modified;
  }
}
