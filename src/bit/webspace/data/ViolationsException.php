<?php

namespace App\bit\webspace\data;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsException extends RuntimeException implements JsonSerializable {

  public function __construct(protected ConstraintViolationListInterface $violations) {
    parent::__construct();
  }

  public function getViolations(): ConstraintViolationListInterface {
    return $this->violations;
  }

  public function asJson(): array {
    $result = [
      'violations' => []
    ];
    foreach ($this->violations as $violation) {
      $result['violations'][] = [
        'message' => $violation->getMessage(),
        'invalidValue' => $violation->getInvalidValue(),
        'propertyPath' => $violation->getPropertyPath(),
      ];
    }
    return $result;
  }
}
