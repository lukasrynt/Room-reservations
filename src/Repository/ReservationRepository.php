<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\States;
use App\Entity\User;
use App\Services\Filter;
use App\Services\Orderer;
use App\Services\Paginator;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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

    public function filter(?array $findFilters, ?array $orderByFilters = null, ?array $paginationFilters = null): array
    {
        $criteria = (new Filter())->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        if ($paginationFilters) {
            $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        }
        return $this->matching($criteria)->toArray();
    }

    public function filterForUser(User $user, ?array $findFilters, ?array $orderByFilters = null, ?array $paginationFilters = null): array
    {
        $criteria = $this->getAllForUserCrit($user);
        $criteria = (new Filter($criteria))->getFilterCriteria($findFilters);
        $criteria = (new Orderer($criteria))->getOrderCriteria($orderByFilters);
        if ($paginationFilters) {
            $criteria = (new Paginator($criteria))->getCriteriaForPage($paginationFilters);
        }
        return $this->matching($criteria)->toArray();
    }

    public function getCollisionReservations(Reservation $reservation): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->gte('timeFrom', new \DateTime($reservation->getTimeFrom())),
                Criteria::expr()->lt('timeFrom', new \DateTime($reservation->getTimeTo()))))
            ->orWhere(Criteria::expr()->andX(
                Criteria::expr()->gt('timeTo', new DateTime($reservation->getTimeFrom())),
                Criteria::expr()->lte('timeTo', new DateTime($reservation->getTimeTo()))))
            ->orWhere(Criteria::expr()->andX(
                Criteria::expr()->lte('timeFrom', new DateTime($reservation->getTimeFrom())),
                Criteria::expr()->gte('timeTo', new DateTime($reservation->getTimeTo()))));

        $criteria = $this->getApprovedCrit($criteria);
        $criteria = $this->getByRoomCrit($reservation->getRoom(), $criteria);
        $criteria = $this->getByDateCrit($reservation->getDate(), $criteria);

        return $this->matching($criteria)->toArray();
    }

    public function getCurrentRoomReservation(Room $room): ?Reservation
    {
        $today = new DateTime();
        $criteria = $this->getByDateCrit($today->format("Y-m-d"));
        $criteria = $this->getByRoomCrit($room, $criteria);
        $criteria = $this->getApprovedCrit($criteria);

        $criteria
            ->andWhere(Criteria::expr()->lte('timeFrom', new DateTime($today->format("H:i:s"))))
            ->andWhere(Criteria::expr()->gte('timeTo', new DateTime($today->format("H:i:s"))));

        return $this->matching($criteria)->toArray()[0] ?? null;
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

    private function getPendingByGroupsCrit(User $groupManager, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        $criteria = $this->getPendingCrit($criteria);
        $managedGroups = $groupManager->getManagedGroups();
        $requestedRooms = $this->roomRepository->filterByGroups($managedGroups);
        return $this->getByRoomsCrit($requestedRooms->getValues(), $criteria);
    }

    private function getPendingByRoomsCrit(User $roomManager, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        $criteria = $this->getPendingCrit($criteria);
        $requestedRooms = $roomManager->getManagedRooms();
        return $this->getByRoomsCrit($requestedRooms->toArray(), $criteria);
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

    private function getByDateCrit(string $date, Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        return $criteria
            ->andWhere(Criteria::expr()->eq('date', new DateTime($date)));
    }

    private function getByRoomCrit(Room $room,  Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        return $criteria
            ->andWhere(Criteria::expr()->eq('room', $room));
    }

    private function getApprovedCrit(Criteria $criteria = null): Criteria
    {
        $criteria ??= Criteria::create();
        return $criteria
            ->andWhere(Criteria::expr()->eq('state', States::APPROVED));
    }
}
