<?php

namespace App\Repository;

use App\Entity\User;
use App\Services\Filter;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\LazyCriteriaCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return Collection|LazyCriteriaCollection
     */
    public function filter(array $filters): Collection
    {
        $criteria = (new Filter())->createQuery($filters);
        $criteria = (new Paginator($criteria, 2))->getCriteriaForPage(1);
        return $this->matching($criteria);
    }
}
