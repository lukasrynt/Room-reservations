<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private UserRepository $userRepository;
    private BuildingRepository $buildingRepository;

    /**
     * @param UserRepository $userRepository
     * @param BuildingRepository $buildingRepository
     */
    public function __construct(UserRepository $userRepository, BuildingRepository $buildingRepository)
    {
        $this->userRepository = $userRepository;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * @Route("/")
     * @return Response
     */
    public function usersIndex(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('users/index.html.twig', ['users' => $users]);
    }
    /**
     * @Route("/buildings")
     * @return Response
     */
    public function buildingsIndex(): Response
    {
        $buildings = $this->buildingRepository->findAll();
        return $this->render('buildings/index.html.twig', ['buildings' => $buildings]);
    }
}