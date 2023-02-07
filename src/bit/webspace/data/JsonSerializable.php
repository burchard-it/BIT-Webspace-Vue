<?php

namespace App\bit\webspace\data;

interface JsonSerializable {
  public function asJson(): array;
}
