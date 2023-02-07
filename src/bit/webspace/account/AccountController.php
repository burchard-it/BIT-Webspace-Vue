<?php

namespace App\bit\webspace\account;

use App\bit\webspace\data\ViolationException;
use App\bit\webspace\rest\RestController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AccountController extends RestController {

  public function __construct(
    LoggerInterface          $logger,
    SerializerInterface      $serializer,
    protected AccountService $accountService,
  ) {
    parent::__construct($logger, $serializer);
  }

  #[Route('/api/account/{id}', name: 'deleteAccountById', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse {
    $this->accountService->remove($id);
    return $this->json(null, 204);
  }

  #[Route('/api/account', name: 'createAccount', methods: ['POST'])]
  public function create(Request $request): JsonResponse {
    /** @var Account $account */
    $account = $this->toEntity($request, Account::class);
    $result = $this->accountService->save($account);
    return $this->json($result, 201);
  }

  #[Route('/api/account/{id}', name: 'findAccountById', methods: ['GET'])]
  public function findById(int $id): JsonResponse {
    return $this->json($this->accountService->findById($id));
  }

  #[Route('/api/account', name: 'searchAccounts', methods: ['GET'])]
  public function search(Request $request): JsonResponse {
    $role = $request->query->get('role');
    if ($role) {
      $accountRole = AccountRole::fromName($role);
      if ($accountRole) {
        return $this->json($this->accountService->findByRole($accountRole));
      }
      return $this->json([]);
    }
    return $this->json($this->accountService->getAll());
  }

  #[Route('/api/account/{id}', name: 'updateAccount', methods: ['POST'])]
  public function update(Request $request, int $id): JsonResponse {
    /** @var Account $account */
    $account = $this->toEntity($request, Account::class);
    if ($account->getId() === null) {
      throw new ViolationException("Property missing", $account->getId(), 'id');
    }
    if ($account->getId() !== $id) {
      throw new ViolationException("path id does not match account id {$account->getId()}", $id, 'id');
    }
    $result = $this->accountService->update($account);
    return $this->json($result);
  }
}
