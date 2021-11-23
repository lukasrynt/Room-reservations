<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\RoomRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/rooms")
 */
class RoomController extends AbstractFOSRestController
{
    private RoomRepository $roomRepository;

    /**
     * @param RoomRepository $roomRepository
     */
    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }


    /**
     * @Route("/", methods={"GET"})
     * @return Response
     */
    public function all(): Response
    {
        $rooms = $this->roomRepository->findAll();
        $view = $this->view($rooms, 200);
        return $this->handleView($view);
    }
}