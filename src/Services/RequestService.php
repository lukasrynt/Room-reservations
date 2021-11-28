<?php

namespace App\Services;

use App\Entity\GroupManager;
use App\Entity\Request;
use App\Entity\Room;
use App\Entity\RoomManager;
use App\Entity\User;
use App\Repository\RequestRepository;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class RequestService
{
    private RequestRepository $requestRepository;
    private RoomRepository $roomRepository;
    private EntityManagerInterface $entityManager;

    /**
     * RoomService constructor.
     * @param RequestRepository $requestRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestRepository $requestRepository, EntityManagerInterface $entityManager)
    {
        $this->requestRepository = $requestRepository;
        $this->entityManager = $entityManager;
    }

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

    public function save(Request $request): void
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }

    public function findNotApprovedRequestsAll(): Collection
    {
        $criteria = $this->getCriteriaNotValid();
        return $this->requestRepository->matching($criteria);
    }

    public function findNotApprovedRequestsByGroup(GroupManager $groupManager): Collection
    {
        $managedGroups = $groupManager->getGroups();
        $requestedRooms = $this->roomRepository->filterByGroups($managedGroups);
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria);
    }

    public function findNotApprovedRequestsByRoom(RoomManager $roomManager): Collection
    {
        $requestedRooms = $roomManager->getManagedRooms();
        $criteria = $this->getCriteriaByIds($requestedRooms);
        return $this->requestRepository->matching($criteria);
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
                ->andWhere(Criteria::expr()->in('room', $ids->map(function($obj){return $obj->getId();})->getValues()));
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