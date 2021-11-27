<?php

namespace App\Repository;

use App\Entity\GroupManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupManager[]    findAll()
 * @method GroupManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupManager::class);
    }

    // /**
    //  * @return GroupManager[] Returns an array of GroupManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupManager
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
