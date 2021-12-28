<?php

namespace App\Repository;

use App\Entity\GroupManager;
use App\Entity\Reservation;
use App\Entity\RoomManager;
use App\Entity\States;
use App\Entity\User;
use App\Services\Filter;
use App\Services\Orderer;
use App\Services\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    private RoomRepository $roomRepository;

    public function __construct(ManagerRegistry $registry, RoomRepository $roomRepository)
    {
        parent::__construct($registry, Reservation::class);
        $this->roomRepository = $roomRepository;
    }

    public function filterAll(?array $findFilters, ?array $orderByFilters, ?array $paginationFilters): array
    {
        $criteria = (new Filter())->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        return $this->matching($criteria)->toArray();
    }

    public function filterAllForUser(User $user, ?array $findFilters, ?array $orderByFilters, ?array $paginationFilters): array
    {
        $criteria = $this->getAllForUserCrit($user);
        $criteria = (new Filter($criteria))->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        return $this->matching($criteria)->toArray();
    }

    private function getAllForUserCrit(User $user): Criteria
    {
        $criteria = Criteria::create();
        if ($user->isAdmin()) {
            $criteria = $this->getPendingCrit($criteria);
        }  elseif ($user->isRoomAdmin()) {
            $criteria = $this->getPendingByRoomsCrit($user, $criteria);
        } elseif ($user->isGroupAdmin()) {
            $criteria = $this->getPendingByGroupsCrit($user, $criteria);
        }
        return $this->getForUserCrit($user, $criteria);
    }

    private function getForUserCrit(User $user, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        return $criteria
            ->orWhere(Criteria::expr()->eq('user', $user));
    }

    private function getPendingByGroupsCrit(GroupManager $groupManager, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        $criteria = $this->getPendingCrit($criteria);
        $managedGroups = $groupManager->getGroups();
        $requestedRooms = $this->roomRepository->filterByGroups($managedGroups);
        return $this->getByRoomsCrit($requestedRooms->getValues(), $criteria);
    }

    private function getPendingByRoomsCrit(RoomManager $roomManager, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        $criteria = $this->getPendingCrit($criteria);
        $requestedRooms = $roomManager->getManagedRooms();
        return $this->getByRoomsCrit($requestedRooms, $criteria);
    }

    private function getByRoomsCrit(array $rooms, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        if (empty($rooms)) {
            return $criteria;
        }
        return $criteria
            ->andWhere(Criteria::expr()->in('room', array_map(fn($obj) => $obj->getId(), $rooms)));
    }

    private function getPendingCrit(Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        return $criteria->andWhere(Criteria::expr()->eq('state', new States("PENDING")));
    }
}
