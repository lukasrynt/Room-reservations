<?php

namespace App\Services;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupService
{
    private GroupRepository $groupRepository;
    private EntityManagerInterface $entityManager;

    /**
     * GroupService constructor.
     * @param GroupRepository $groupRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(GroupRepository $groupRepository, EntityManagerInterface $entityManager)
    {
        $this->groupRepository = $groupRepository;
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
}