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
    private RoomManagerRepository $roomManagerRepository;
    private GroupManagerRepository $groupManagerRepository;
    private EntityManagerInterface $entityManager;
    private RoomRepository $roomRepository;

    /**
     * RoomService constructor.
     * @param RequestRepository $requestRepository
     * @param RoomManagerRepository $roomManagerRepository
     * @param GroupManagerRepository $groupManagerRepository
     * @param EntityManagerInterface $entityManager
     * @param RoomRepository $roomRepository
     */
    public function __construct(RequestRepository $requestRepository,
                                RoomManagerRepository $roomManagerRepository,
                                GroupManagerRepository $groupManagerRepository,
                                EntityManagerInterface $entityManager,
                                RoomRepository $roomRepository)
    {
        $this->groupManagerRepository = $groupManagerRepository;
        $this->roomManagerRepository = $roomManagerRepository;
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

    public function findAllFor(User $user): array
    {
        $criteria = ['state' => 'PENDING'];
        if ($user->isRoomAdmin()) {
            $criteria['room_id'] = $user->getRooms();
        }
        # else if ($user->isGroupAdmin())
        #   TODO: return only rooms for the group
        else if ($user->isCommonUser()) {
            return [];
        }
        return $this->requestRepository->findBy($criteria);
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
     * @return array|array
     */
    public function getRequestsToConfirmFor(User $user): array
    {
        if ($user->isAdmin())
            return self::findNotApprovedRequestsAll();
        elseif ($user->isRoomAdmin()) {
            $roomAdmin = $this->roomManagerRepository->find($user->getId());
            return self::findNotApprovedRequestsByRoom($roomAdmin);
        } elseif ($user->isGroupAdmin()) {
            $groupAdmin = $this->groupManagerRepository->find($user->getId());
            return self::findNotApprovedRequestsByGroup($groupAdmin);
        }
        return [];
    }

    /**
     * @return array
     */
    public function findNotApprovedRequestsAll(): array
    {
        $criteria = $this->getCriteriaNotValid();
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param GroupManager $groupManager
     * @return array
     */
    public function findNotApprovedRequestsByGroup(GroupManager $groupManager): array
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
    public function findNotApprovedRequestsByRoom(RoomManager $roomManager): array
    {
        $requestedRooms = $roomManager->getManagedRooms();
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param Collection|Room[] $ids
     * @return Criteria
     */
    public function getCriteriaByIds(Collection $ids): Criteria
    {
        $criteria = $this->getCriteriaNotValid();
        if (!$ids->isEmpty())
            return $criteria
                ->andWhere(Criteria::expr()->in('room', $ids->map(fn($obj) => $obj->getId())->getValues()));
        return $criteria;
    }

    /**
     * @return Criteria
     */
    public function getCriteriaNotValid(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('state', new States("PENDING")));
    }
}