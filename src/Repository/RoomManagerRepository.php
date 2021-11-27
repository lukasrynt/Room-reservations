<?php

namespace App\Repository;

use App\Entity\RoomManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomManager[]    findAll()
 * @method RoomManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomManager::class);
    }

    // /**
    //  * @return RoomManager[] Returns an array of RoomManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoomManager
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
