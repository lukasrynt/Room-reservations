<?php

namespace App\Services;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;

class RoomService
{
    private RoomRepository $roomRepository;
    private EntityManagerInterface $entityManager;

    /**
     * RoomService constructor.
     * @param RoomRepository $roomRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RoomRepository $roomRepository, EntityManagerInterface $entityManager)
    {
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }

    public function find(int $id): ?Room
    {
        return $this->roomRepository->find($id);
    }

    /**
     * @return Room[]|array
     */
    public function findAll(): array
    {
        return $this->roomRepository->findAll();
    }

    public function save(Room $room): void
    {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function delete(Room $room): void
    {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }

    /**
     * @param array $queryParams
     * @return array
     */
    public function filter(array $queryParams): array
    {
        return $this->roomRepository->filter(
            ParamsParser::getFilters($queryParams, 'filter_by'),
            ParamsParser::getFilters($queryParams, 'order_by'),
            ParamsParser::getFilters($queryParams, 'paginate')
        );
    }

    /**
     * @param Collection $groups
     * @return Collection
     */
    public function findByGroups(Collection $groups): Collection
    {
        return $this->roomRepository->filterByGroups($groups);
    }

    public function findAllPublic(): array
    {
        return $this->roomRepository->findAllPublic();
    }
}