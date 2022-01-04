<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use App\Entity\GroupManager;
use App\Entity\RoomManager;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private RoomService $roomService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param RoomService $roomService
     */
    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                RoomService $roomService)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->roomService = $roomService;
    }


    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @return User[]|array
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param array $queryParams
     * @return array
     */
    public function filter(array $queryParams): array
    {
        return $this->userRepository->filter(
            $queryParams['filter_by'] ?? null,
            $queryParams['order_by'] ?? null,
            $queryParams['paginate'] ?? null
        );
    }

    public function countForParams(array $queryParams): int
    {
        return count($this->userRepository->filter($queryParams['filter_by']));
    }

    public function search(array $searchParams): array
    {
        return $this->userRepository->search($searchParams);
    }

    public function getManagedRoomsByRoomAdmin(User $user): array
    {
        return $user->getManagedRooms()->toArray();
    }

    public function getManagedRoomsByGroupAdmin(User $user): array
    {
        $managedGroups = clone $user->getAllManagedGroups();
        $rooms = $this->roomService->findByGroups($managedGroups);

        foreach ($user->getManagedRooms() as $room){
            $rooms->add($room);
        }

        return array_unique($rooms->toArray());
    }

    public function getRoomsForUser(User $user): array
    {
        if ($user->isAdmin()) {
            return $this->roomService->findAll();
        } else if ($user->isGroupAdmin()) {
            return $this->getManagedRoomsByGroupAdmin($user);
        } else if ($user->isRoomAdmin()) {
            return $this->getManagedRoomsByRoomAdmin($user);
        } else {
            return [];
        }
    }
}