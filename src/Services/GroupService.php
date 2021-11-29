<?php

namespace App\Services;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;

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
     * @return array
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
     * @return Group
     */
    public function addUser(int $groupId, int $userId): Group
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);
        $group->addMember($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function removeUser(int $groupId, int $userId): Group
    {
        $group = $this->groupRepository->find($groupId);
        $user = $this->userRepository->find($userId);
        $group->removeMember($user);
        $this->entityManager->persist($group);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $group;
    }

    /**
     * @param int $groupId
     * @param int $roomId
     * @return Group
     */
    public function addRoom(int $groupId, int $roomId): Group
    {
        $group = $this->groupRepository->find($groupId);
        $room = $this->roomRepository->find($roomId);
        $group->addRoom($room);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function removeRoom(int $groupId, int $roomId): Group
    {
        $group = $this->groupRepository->find($groupId);
        $room = $this->roomRepository->find($roomId);
        $group->removeRoom($room);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }
}