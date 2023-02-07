<?php

namespace App\bit\webspace\data;

use RuntimeException;

class EntityNotFoundException extends RuntimeException implements JsonSerializable {

  public function asJson(): array {
    return ['error' => ['message' => $this->getMessage(), 'code' => 404]];
  }
}
