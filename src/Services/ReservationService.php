<?php


namespace App\Services;


use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\States;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    private ReservationRepository $reservationRepository;
    private EntityManagerInterface $entityManager;

    /**
     * ReservationService constructor.
     * @param ReservationRepository $reservationRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ReservationRepository $reservationRepository, EntityManagerInterface $entityManager)
    {
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Reservation|null
     */
    public function find(int $id): ?Reservation
    {
        return $this->reservationRepository->find($id);
    }

    /**
     * @return Reservation[]|array
     */
    public function findAll(): array
    {
        return $this->reservationRepository->findAll();
    }

    public function save(Reservation $reservation): void
    {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    public function delete(Reservation $reservation): void
    {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
    }

    public function filter(array $queryParams): array
    {
        return $this->reservationRepository->filter(
            $queryParams['filter_by'] ?? null,
            $queryParams['order_by'] ?? null,
            $queryParams['paginate'] ?? null
        );
    }

    /**
     * @param User $user
     * @param array $queryParams
     * @return array
     */
    public function filterForUser(array $queryParams, User $user): array
    {
        return $this->reservationRepository->filterForUser(
            $user,
            $queryParams['filter_by'] ?? null,
            $queryParams['order_by'] ?? null,
            $queryParams['paginate'] ?? null
        );
    }

    public function countForParamsAndUser(array $queryParams, ?User $user): int
    {
        return count($this->reservationRepository->filterForUser($user, $queryParams['filter_by']));
    }

    public function newWithRequesterAndRoom(User $user, Room $room) : Reservation
    {
        $request = new Reservation();
        $request->setUser($user);
        $request->setState(new States("PENDING"));
        $request->setRoom($room);
        return $request;
    }

    public function getCurrentReservation(Room $room) : ?Reservation
    {
        $reservation = $this->reservationRepository->getCurrentRoomReservation($room);
        return ($reservation) ? $reservation : null;
    }

    public function checkCollisionReservations(Reservation $reservation): Bool
    {
        $reservations = $this->reservationRepository->getCollisionReservations($reservation);
        return empty($reservations);
    }

    public function getActiveForUser(User $user): array
    {
        return $this->reservationRepository->getActiveForUser($user);
    }

    public function getActiveForRoom(Room $room): array
    {
        return $this->reservationRepository->getActiveForRoom($room);
    }
}