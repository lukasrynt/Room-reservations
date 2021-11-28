<?php

namespace App\Repository;

use App\Entity\Room;
use App\Services\Filter;
use App\Services\Orderer;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\LazyCriteriaCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Collection;

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
    public function filter(?array $findFilters, ?array $orderByFilters, ?array $paginationFilters): array
    {
        $criteria = (new Filter())->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        return $this->matching($criteria)->toArray();
    }
}
