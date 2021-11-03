<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller;

use App\Repository\BuildingRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private UserRepository $userRepository;
    private RoomRepository $roomRepository;
    private BuildingRepository $buildingRepository;

    /**
     * @param UserRepository $userRepository
     * @param RoomRepository $roomRepository
     * @param BuildingRepository $buildingRepository
     */
    public function __construct(UserRepository $userRepository,
                                RoomRepository $roomRepository,
                                BuildingRepository $buildingRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * @Route("/")
     * @return Response
     */
    public function usersIndex(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('landingPage.html.twig');
    }

    /**
     * @Route("/rooms/")
     * @return Response
     */
    public function roomsIndex(): Response
    {
        $rooms = $this->roomRepository->findAll();
        return $this-> render('rooms/index.html.twig', ['rooms' => $rooms]);
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