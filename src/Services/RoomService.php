<?php

namespace App\Services;

use App\Entity\Group;
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

    /**
     * @param Collection $groups
     * @return Collection
     */
    public function findByGroups(Collection $groups): Collection
    {
        return $this->roomRepository->filterByGroups($groups);
    }
}