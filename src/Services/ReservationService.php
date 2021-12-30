<?php


namespace App\Services;


use App\Entity\Request;
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

    public function filterAll(array $queryParams): array
    {
        return $this->reservationRepository->filterAll(
            ParamsParser::getFilters($queryParams, 'filter_by'),
            ParamsParser::getFilters($queryParams, 'order_by'),
            ParamsParser::getFilters($queryParams, 'paginate')
        );
    }

    /**
     * @param User $user
     * @param array $queryParams
     * @return array
     */
    public function filterAllForUser(User $user, array $queryParams): array
    {
        return $this->reservationRepository->filterAllForUser(
            $user,
            ParamsParser::getFilters($queryParams, 'filter_by'),
            ParamsParser::getFilters($queryParams, 'order_by'),
            ParamsParser::getFilters($queryParams, 'paginate')
        );
    }

    public function newWithRequesterAndRoom(User $user, Room $room) : Reservation
    {
        $request = new Reservation();
        $request->setUser($user);
        $request->setState(new States("PENDING"));
        $request->setRoom($room);
        return $request;
    }
}