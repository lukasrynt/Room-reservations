<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller;

use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private UserRepository $userRepository;
    private RoomRepository $roomRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, RoomRepository $roomRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomRepository = $roomRepository;
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
     * @Route("/rooms/")
     * @return Response
     */
    public function roomsIndex(): Response
    {
        $rooms = $this->roomRepository->findAll();
        return $this-> render('rooms/index.html.twig', ['rooms' => $rooms]);
    }
}