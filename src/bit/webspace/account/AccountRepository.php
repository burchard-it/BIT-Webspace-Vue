<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\bit\webspace\account;

use App\bit\webspace\data\EntityNotFoundException;
use App\bit\webspace\data\ViolationException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository {
  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Account::class);
  }

  /**
   * @param AccountRole $role
   * @return Account[]
   */
  public function findByRole(AccountRole $role): array {
    return $this->createQueryBuilder('a')
      ->andWhere('a.roles LIKE :role')
      ->setParameter('role', "%$role->name%")
      ->orderBy('a.modified', 'DESC')
      ->setFirstResult(0)
      ->setMaxResults(10)
      ->getQuery()
      ->getResult();
  }

  /**
   * @param Account $account
   * @param bool $flush defaults to true
   * @return void
   */
  public function remove(Account $account, bool $flush = true): void {
    $this->getEntityManager()->remove($account);
    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  /**
   * @param Account $account
   * @param bool $flush defaults to true
   * @return void
   */
  public function save(Account $account, bool $flush = true): void {
    $this->getEntityManager()->persist($account);
    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  /**
   * @param Account $account
   * @param bool $flush defaults to true
   * @return Account
   */
  public function update(Account $account, bool $flush = true): Account {
    $entity = $this->find($account->getId(), LockMode::OPTIMISTIC, $account->getVnr());
    if (!$entity) {
      throw new EntityNotFoundException("Account with id {$account->getId()} and vnr {$account->getVnr()} not found");
    }
    $entity->setEmail($account->getEmail());
    $entity->setEnabled($account->isEnabled());
    $entity->setPassword($account->getPassword());
    $entity->setRoles($account->getRoles());
    if ($entity->getUsername() !== $account->getUsername()) {
      $existing = $this->findOneBy(['username' => $account->getUsername()]);
      if ($existing != null) {
        throw new ViolationException('This value is a duplicate', $account->getUsername(), 'username');
      }
      $entity->setUsername($account->getUsername());
    }
    if ($flush) {
      $this->getEntityManager()->flush();
    }
    return $entity;
  }

//    public function findOneBySomeField($value): ?Account
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
