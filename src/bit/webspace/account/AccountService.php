<?php

namespace App\bit\webspace\account;

use App\bit\webspace\data\EntityNotFoundException;
use App\bit\webspace\data\ViolationException;
use App\bit\webspace\data\ViolationsException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountService {

  /**
   * @param LoggerInterface $logger
   * @param ValidatorInterface $validator
   * @param AccountRepository $accountRepository
   */
  public function __construct(
    protected LoggerInterface    $logger,
    protected ValidatorInterface $validator,
    protected AccountRepository  $accountRepository,
  ) {
  }

  /**
   * @param string $id
   * @return Account
   */
  public function findById(string $id): Account {
    $account = $this->accountRepository->find($id);
    if (!$account) {
      throw new EntityNotFoundException("Account with id $id not found");
    }
    return $account;
  }

  /**
   * @param AccountRole $role
   * @return Account[]
   */
  public function findByRole(AccountRole $role): array {
    return $this->accountRepository->findByRole($role);
  }

  /**
   * @return Account[]
   */
  public function getAll(): array {
    return $this->accountRepository->findBy([], ['modified' => 'DESC'], 10, 0);
  }

  /**
   * @param string $id
   * @return void
   */
  public function remove(string $id): void {
    $account = $this->accountRepository->find($id);
    if (!$account) {
      throw new EntityNotFoundException("Account with id $id not found");
    }
    $this->accountRepository->remove($account);
  }

  /**
   * @param Account $account
   * @return Account
   */
  public function save(Account $account): Account {
    $this->normalizeAndValidate($account);
    $existing = $this->accountRepository->findOneBy(['username' => $account->getUsername()]);
    if ($existing != null) {
      throw new ViolationException('This value is a duplicate', $account->getUsername(), 'username');
    }
    $this->accountRepository->save($account);
    $this->logger->info('Account persisted {id}', ['id' => $account->getId()]);
    return $account;
  }

  /**
   * @param Account $account
   * @param array $groups supported groups: ['Default', 'update']
   * @return void
   */
  private function normalizeAndValidate(Account $account, array $groups = ['Default']): void {
    $violations = $this->validator->validate($account, null, $groups);
    if ($violations->count() > 0) {
      throw new ViolationsException($violations);
    }
    $account->setUsername(strtolower($account->getUsername()));
    $account->setEmail(strtolower($account->getEmail()));
    $roles = $account->getRoles();
    sort($roles);
    $account->setRoles($roles);
  }

  /**
   * @param Account $account
   * @return Account
   */
  public function update(Account $account): Account {
    $this->normalizeAndValidate($account, ['update', 'Default']);
    return $this->accountRepository->update($account);
  }
}
