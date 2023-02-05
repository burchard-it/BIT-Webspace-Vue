<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

class AccountController extends RestController {

  #[Route('/api/account', name: 'account_create', methods: ['PUT'])]
  public function create(Request $request): JsonResponse {
    try {
      $account = $this->toEntity($request, Account::class);
      $account->addRole(AccountRole::User);
      $violations = $this->validator->validate($account);
      if ($violations->count() > 0) {
        return $this->violationsToJson($violations);
      }
      return $this->toJson($account, 201);
    } catch (NotEncodableValueException|NotNormalizableValueException $ex) {
      return $this->json(['msg' => "'{$request->getContent()}' is not a valid contact request: {$ex->getMessage()}"], 400);
    }
  }
}
