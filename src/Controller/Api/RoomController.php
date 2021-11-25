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
 * @Route("/api/rooms", name="_api_rooms")
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
     * @Route("/", methods={"GET"}, name="list")
     * @return Response
     */
    public function all(): Response
    {
        $rooms = $this->roomRepository->findAll();
        $view = $this->view($rooms, 200);
        return $this->handleView($view);
    }

    /**
     * @Route("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->roomRepository->find($id);
        $view = $this->view($request, 200);
        return $this->handleView($view);
    }
}