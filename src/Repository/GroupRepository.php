<?php

namespace App\Repository;

use App\Entity\Group;
use App\Services\Filter;
use App\Services\Orderer;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @param array|null $findFilters
     * @param array|null $orderByFilters
     * @param array|null $paginationFilters
     * @return array
     */
    public function filter(?array $findFilters, ?array $orderByFilters = null, ?array $paginationFilters = null): array
    {
        $criteria = (new Filter())->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        if ($paginationFilters) {
            $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        }
        return $this->matching($criteria)->toArray();
    }
}
