<?php

namespace App\Repository;

use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invitation>
 *
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Invitation::class);
  }

  /**
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function add(Invitation $entity, bool $flush = true): void
  {
    $this->_em->persist($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }

  /**
   * @throws ORMException
   * @throws OptimisticLockException
   */
  public function remove(Invitation $entity, bool $flush = true): void
  {
    $this->_em->remove($entity);
    if ($flush) {
      $this->_em->flush();
    }
  }

  /**
   * Get the sent invitation for the given user id.
   *
   * @param integer $userId
   * @param integer $invitationId
   * @return User|null
   */
  public function getInvitedBy(int $userId, int $invitationId)
  {
    return $this->createQueryBuilder('i')
      ->join('i.sender', 'u')
      ->andWhere('i.id = :invitation')
      ->andWhere('u.id = :user')
      ->setParameter('invitation', $invitationId)
      ->setParameter('user', $userId)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
   * Get the received invitation for the given user id
   *
   * @param integer $userId
   * @param integer $invitationId
   * @return User|null
   */
  public function getInvitedTo(int $userId, int $invitationId)
  {
    return $this->createQueryBuilder('i')
      ->join('i.receiver', 'u')
      ->andWhere('i.id = :invitation')
      ->andWhere('u.id = :user')
      ->setParameter('invitation', $invitationId)
      ->setParameter('user', $userId)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
