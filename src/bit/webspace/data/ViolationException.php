<?php

namespace App\bit\webspace\data;

use RuntimeException;

class ViolationException extends RuntimeException implements JsonSerializable {

  private mixed $invalidValue;
  private ?string $propertyPath;

  public function __construct(?string $message, mixed $invalidValue, ?string $propertyPath = null) {
    parent::__construct($message);
    $this->invalidValue = $invalidValue;
    $this->propertyPath = $propertyPath;
  }

  public function asJson(): array {
    return ['violation' => [
      'message' => $this->getMessage(),
      'invalidValue' => $this->invalidValue,
      'propertyPath' => $this->propertyPath,
    ]];
  }
}
