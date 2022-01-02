<?php

namespace App\Repository;

use App\Entity\Room;
use App\Entity\User;
use App\Services\Filter;
use App\Services\Orderer;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
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

    /**
     * @param User|null $user
     * @param array|null $findFilters
     * @param array|null $orderByFilters
     * @param array|null $paginationFilters
     * @return array
     */
    public function filterForUser(?User $user, ?array $findFilters, ?array $orderByFilters = null, ?array $paginationFilters = null): array
    {
        $criteria = Criteria::create();
        if (!$user) {
            $criteria = $criteria->andWhere(Criteria::expr()->eq('private', false));
        }
        $criteria = (new Filter($criteria))->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        if ($paginationFilters) {
            $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        }
        return $this->matching($criteria)->toArray();
    }

    /**
     * @param Collection $groups
     * @return Collection
     */
    public function filterByGroups(Collection $groups): Collection
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->in('id', $groups->map(function($obj){return $obj->getId();})->getValues()));
        return $this->matching($criteria);
    }

    /**
     * @return array
     */
    public function findAllPublic(): array
    {
        return $this->matching(
            Criteria::create()
                ->andWhere(Criteria::expr()->eq('private', false))
        )->toArray();
    }
}
