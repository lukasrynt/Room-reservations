<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\RoomRepository;
use App\Services\RoomService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/rooms", name="_api_rooms")
 */
class RoomController extends AbstractFOSRestController
{
    private RoomService $roomService;

    /**
     * @param RoomService $roomService
     */
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $rooms = $this->roomService->filter($request->query->all());
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
        $request = $this->roomService->find($id);
        $view = $this->view($request, 200);
        return $this->handleView($view);
    }
}