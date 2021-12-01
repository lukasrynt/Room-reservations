<?php


namespace App\Services;


use App\Entity\GroupManager;
use App\Repository\GroupManagerRepository;

class GroupManagerService
{
    private GroupManagerRepository $groupManagerRepository;

    /**
     * GroupManagerService constructor.
     * @param GroupManagerRepository $groupManagerRepository
     */
    public function __construct(GroupManagerRepository $groupManagerRepository)
    {
        $this->groupManagerRepository = $groupManagerRepository;
    }

    /**
     * @param int $id
     * @return GroupManager
     */
    public function find(int $id): GroupManager
    {
        return $this->groupManagerRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->groupManagerRepository->findAll();
    }
}