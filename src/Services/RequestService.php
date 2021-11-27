<?php

namespace App\Services;

use App\Entity\Request;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequestService
{
    private RequestRepository $requestRepository;
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
     * @return Request[]|array
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
}