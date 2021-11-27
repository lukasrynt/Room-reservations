<?php


namespace App\Services;


use App\Entity\RoomUser;
use App\Repository\RoomUserRepository;

class RoomUserService
{
    private RoomUserRepository $roomUserRepository;

    /**
     * RoomUserService constructor.
     * @param RoomUserRepository $roomUserRepository
     */
    public function __construct(RoomUserRepository $roomUserRepository)
    {
        $this->roomUserRepository = $roomUserRepository;
    }

    /**
     * @param int $id
     * @return RoomUser
     */
    public function find(int $id): RoomUser
    {
        return $this->roomUserRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->roomUserRepository->findAll();
    }
}