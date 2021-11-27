<?php


namespace App\Services;


use App\Entity\RoomManager;
use App\Repository\RoomManagerRepository;
use Doctrine\Common\Collections\Collection;

class RoomManagerService
{
    private RoomManagerRepository $roomManagerRepository;

    /**
     * RoomManagerService constructor.
     * @param RoomManagerRepository $roomManagerRepository
     */
    public function __construct(RoomManagerRepository $roomManagerRepository)
    {
        $this->roomManagerRepository = $roomManagerRepository;
    }

    /**
     * @param int $id
     * @return RoomManager
     */
    public function find(int $id): RoomManager
    {
        return $this->roomManagerRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->roomManagerRepository->findAll();
    }
}