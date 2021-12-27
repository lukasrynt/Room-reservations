<?php

namespace App\Services;

use App\Entity\EnumStateType;
use App\Entity\GroupManager;
use App\Entity\Request;
use App\Entity\Room;
use App\Entity\RoomManager;
use App\Entity\States;
use App\Entity\User;
use App\Repository\GroupManagerRepository;
use App\Repository\RequestRepository;
use App\Repository\RoomManagerRepository;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class RequestService
{
    private RequestRepository $requestRepository;
    private EntityManagerInterface $entityManager;
    private RoomRepository $roomRepository;

    /**
     * RoomService constructor.
     * @param RequestRepository $requestRepository
     * @param EntityManagerInterface $entityManager
     * @param RoomRepository $roomRepository
     */
    public function __construct(RequestRepository $requestRepository,
                                EntityManagerInterface $entityManager,
                                RoomRepository $roomRepository)
    {
        $this->requestRepository = $requestRepository;
        $this->entityManager = $entityManager;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param int $id
     * @return Request|null
     */
    public function find(int $id): ?Request
    {
        return $this->requestRepository->find($id);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->requestRepository->findAll();
    }

    /**
     * @param Request $request
     */
    public function save(Request $request): void
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }

    public function newWithRequestorAndRoom(User $user, Room $room) : Request
    {
        $newRequest = new Request();
        $newRequest->setRequestor($user);
        $newRequest->setState(new States("PENDING"));
        $newRequest->setRoom($room);
        return $newRequest;
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllFor(User $user): array
    {
        if ($user->isAdmin()) {
            return self::findNotApprovedRequestsAll();
        }  elseif ($user->isRoomAdmin()) {
            return self::findNotApprovedRequestsByRoom($user);
        } elseif ($user->isGroupAdmin()) {
            return self::findNotApprovedRequestsByGroup($user);
        }
        return [];
    }

    /**
     * @return array
     */
    private function findNotApprovedRequestsAll(): array
    {
        $criteria = $this->getCriteriaNotValid();
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param GroupManager $groupManager
     * @return array
     */
    private function findNotApprovedRequestsByGroup(GroupManager $groupManager): array
    {
        $managedGroups = $groupManager->getGroups();
        $requestedRooms = $this->roomRepository->filterByGroups($managedGroups);
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param RoomManager $roomManager
     * @return array
     */
    private function findNotApprovedRequestsByRoom(RoomManager $roomManager): array
    {
        $requestedRooms = $roomManager->getManagedRooms();
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param Collection|Room[] $ids
     * @return Criteria
     */
    private function getCriteriaByIds(Collection $ids): Criteria
    {
        $criteria = $this->getCriteriaNotValid();
        if (!$ids->isEmpty()) {
            return $criteria
                ->andWhere(Criteria::expr()->in('room', $ids->map(fn($obj) => $obj->getId())->getValues()));
        }
        return $criteria;
    }

    /**
     * @return Criteria
     */
    private function getCriteriaNotValid(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('state', new States("PENDING")));
    }
}