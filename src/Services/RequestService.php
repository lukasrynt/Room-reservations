<?php

namespace App\Services;

use App\Entity\GroupManager;
use App\Entity\Request;
use App\Entity\Room;
use App\Entity\RoomManager;
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

    /**
     * RoomService constructor.
     * @param RequestRepository $requestRepository
     * @param RoomManagerRepository $roomManagerRepository
     * @param GroupManagerRepository $groupManagerRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestRepository $requestRepository,
                                RoomManagerRepository $roomManagerRepository,
                                GroupManagerRepository $groupManagerRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->groupManagerRepository = $groupManagerRepository;
        $this->roomManagerRepository = $roomManagerRepository;
        $this->requestRepository = $requestRepository;
        $this->entityManager = $entityManager;
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

    public function getRequestsToConfirmFor(User $user): ?Collection
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
        return null;
    }

    /**
     * @return Collection
     */
    public function findNotApprovedRequestsAll(): Collection
    {
        $criteria = $this->getCriteriaNotValid();
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param GroupManager $groupManager
     * @return Collection
     */
    public function findNotApprovedRequestsByGroup(GroupManager $groupManager): Collection
    {
        $managedGroups = $groupManager->getGroups();
        $requestedRooms = $this->roomRepository->filterByGroups($managedGroups);
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria)->getValues();
    }

    /**
     * @param RoomManager $roomManager
     * @return Collection
     */
    public function findNotApprovedRequestsByRoom(RoomManager $roomManager): Collection
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
            ->andWhere(Criteria::expr()->eq('valid', false));
    }
}