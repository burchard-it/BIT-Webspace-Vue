<?php

namespace App\bit\webspace\account;

use ValueError;

enum AccountRole {
  case Newsletter;
  case SystemAdmin;
  case User;

  public static function fromName(string $name): ?AccountRole {
    $strLow = strtolower($name);
    foreach (self::cases() as $role) {
      if ($strLow === strtolower($role->name)) {
        return $role;
      }
    }
    return null;
  }
}
