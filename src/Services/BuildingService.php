<?php

namespace App\Services;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;

class BuildingService
{
    private BuildingRepository $buildingRepository;
    private EntityManagerInterface $entityManager;

    /**
     * BuildingService constructor.
     * @param BuildingRepository $buildingRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(BuildingRepository $buildingRepository, EntityManagerInterface $entityManager)
    {
        $this->buildingRepository = $buildingRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Building|null
     */
    public function find(int $id): ?Building
    {
        return $this->buildingRepository->find($id);
    }

    /**
     * @return Building[]|array
     */
    public function findAll(): array
    {
        return $this->buildingRepository->findAll();
    }

    public function save(Building $building): void
    {
        $this->entityManager->persist($building);
        $this->entityManager->flush();
    }

    /**
     * @param array $queryParams
     * @return array
     */
    public function filter(array $queryParams): array
    {
        return $this->buildingRepository->filter(
            $queryParams['filter_by'] ?? null,
            $queryParams['order_by'] ?? null,
            $queryParams['paginate'] ?? null
        );
    }

    public function countForParams(array $queryParams): int
    {
        return count($this->buildingRepository->filter($queryParams['filter_by']));
    }

}