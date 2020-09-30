<?php

namespace App\Repository;

use App\Entity\Wishlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wishlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wishlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wishlist[]    findAll()
 * @method Wishlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wishlist::class);
    }

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function removeOne(int $id)
    {
        $entity = $this->find($id);

        if ($entity) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Wishlist $entity
     * @return int|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Wishlist $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity->getId();
    }

    /**
     * @param $value
     * @param int $limit
     * @return Wishlist[]
     */
    public function findManyByUserId($value, $limit = 100)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.userId = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @param $userId
     * @return Wishlist|null
     * @throws NonUniqueResultException
     */
    public function findOneForUser($id, $userId): ?Wishlist
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id = :id')
            ->andWhere('w.userId = :userId')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
