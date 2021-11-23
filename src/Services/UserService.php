<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return User
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

    public function filter(array $filters): array
    {
        return $this->userRepository->filter($filters);
    }
}