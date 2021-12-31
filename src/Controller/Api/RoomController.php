<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use App\Services\ReservationService;
use App\Services\RoomService;
use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
    private UserService $userService;
    private ReservationService $reservationService;

    /**
     * RoomController constructor.
     * @param RoomService $roomService
     * @param UserService $userService
     * @param ReservationService $reservationService
     */
    public function __construct(RoomService $roomService, UserService $userService, ReservationService $reservationService)
    {
        $this->roomService = $roomService;
        $this->userService = $userService;
        $this->reservationService = $reservationService;
    }


    /**
     * @Rest\Get("/", name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $rooms = $this->roomService->filter($request->query->all());
        $view = $this->view($rooms, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->roomService->find($id);
        $view = $this->view($request, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{id}/door", name="lock_unlock_door")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function lockUnlockDoor(Request $request, int $id): Response
    {
        $userId = $request->get('user_id');
        $user = $this->userService->find($userId);
        $room = $this->roomService->find($id);

        if (!$user || !$room)
            return $this->handleView($this->view([], Response::HTTP_NOT_FOUND));

        if (!$this->hasUserPermission($user, $room))
            return $this->handleView($this->view("Access forbidden", Response::HTTP_FORBIDDEN));

        if ($room->getAccessCounter() == 1 && (!$room->getLastAccess() || $room->getLastAccess() == $userId))
        {
            $room->setAccessCounter(0);
            if ($room->getLocked())
            {
                $room->setLocked(false);
                $message = "Unlocking the door";
            }else {
                $room->setLocked(true);
                $message = "Locking the door";
            }

            $this->roomService->save($room);
            return $this->handleView($this->view($message, Response::HTTP_OK));
        }

        $room->setAccessCounter(1);
        $room->setLastAccess($userId);
        $this->roomService->save($room);
        if (!$room->getLocked())
            return $this->handleView($this->view("Opening the door", Response::HTTP_OK));
        return $this->handleView($this->view("Access counter to unlock the door: 1/2", Response::HTTP_OK));
    }

    private function hasUserPermission(User $user, Room $room): bool
    {
        if ($user->isAdmin() ||
            $room->getRoomManager() === $user ||
            ($user->isGroupAdmin() && $room->getGroup() === $user->getGroup()))
            return true;

        $currentReservation = $this->reservationService->getCurrentReservation($room);
        if (!$currentReservation)
            return false;

        return $currentReservation->getAttendees()->contains($user) || $currentReservation->getUser() === $user;
    }
}