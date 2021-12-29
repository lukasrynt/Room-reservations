<?php

namespace App\Services;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Doctrine\Common\Collections\Collection;

class GroupService
{
    private GroupRepository $groupRepository;
    private UserRepository $userRepository;
    private RoomRepository $roomRepository;
    private EntityManagerInterface $entityManager;

    /**
     * GroupService constructor.
     * @param GroupRepository $groupRepository
     * @param UserRepository $userRepository
     * @param RoomRepository $roomRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(GroupRepository $groupRepository,
                                UserRepository $userRepository,
                                RoomRepository $roomRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Group[]|array
     */
    public function findAll(): array
    {
        return $this->groupRepository->findAll();
    }

    /**
     * @param array $queryParams
     * @return Group[]|array
     */
    public function filter(array $queryParams): array
    {
        return $this->groupRepository->filter(
            ParamsParser::getFilters($queryParams, 'filter_by'),
            ParamsParser::getFilters($queryParams, 'order_by'),
            ParamsParser::getFilters($queryParams, 'paginate')
        );
    }

    /**
     * @param int $groupId
     * @param int $userId
     * @return Group|null
     */
    public function addUser(int $groupId, int $userId): ?Group
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);
        if (!$user || !$group)
            return null;

        $group->addMember($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param int $groupId
     * @param int $userId
     * @return Group|null
     */
    public function removeUser(int $groupId, int $userId): ?Group
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);
        if (!$user || !$group)
            return null;

        $group->removeMember($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param int $groupId
     * @param int $roomId
     * @return Group
     */
    public function addRoom(int $groupId, int $roomId): ?Group
    {
        $group = $this->groupRepository->find($groupId);
        $room = $this->roomRepository->find($roomId);
        if (!$room || !$group)
            return null;

        $group->addRoom($room);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param int $groupId
     * @param int $roomId
     * @return Group|null
     */
    public function removeRoom(int $groupId, int $roomId): ?Group
    {
        $group = $this->groupRepository->find($groupId);
        $room = $this->roomRepository->find($roomId);
        if (!$room || !$group)
            return null;

        $group->removeRoom($room);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function find(int $id): ?Group
    {
        return $this->groupRepository->find($id);
    }

    public function save(Group $group): void
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
}