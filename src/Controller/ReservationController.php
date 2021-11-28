<?php


namespace App\Controller;


use App\Entity\Reservation;
use App\Form\Type\ReservationType;
use App\Repository\ReservationRepository;
use App\Services\GroupManagerService;
use App\Services\RequestService;
use App\Services\ReservationService;
use App\Services\RoomManagerService;
use App\Services\RoomService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservations", name="reservations_")
 */
class ReservationController extends AbstractController
{
    private ReservationService $reservationService;
    private UserService $userService;
    private RoomService $roomService;
    private RoomManagerService $roomManagerService;
    private GroupManagerService $groupManagerService;
    private RequestService $requestService;

    /**
     * ReservationController constructor.
     * @param ReservationService $reservationService
     * @param UserService $userService
     * @param RoomService $roomService
     * @param RoomManagerService $roomManagerService
     * @param GroupManagerService $groupManagerService
     * @param RequestService $requestService
     */
    public function __construct(ReservationService $reservationService, UserService $userService, RoomService $roomService, RoomManagerService $roomManagerService, GroupManagerService $groupManagerService, RequestService $requestService)
    {
        $this->reservationService = $reservationService;
        $this->userService = $userService;
        $this->roomService = $roomService;
        $this->roomManagerService = $roomManagerService;
        $this->groupManagerService = $groupManagerService;
        $this->requestService = $requestService;
    }


    /**
     * @Route("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation)
            return $this->render('errors/404.html.twig');
        return $this->render('reservations/detail.html.twig', [
            'reservation' => $reservation
        ]);
    }


    /**
     * @Route("/new_from_request/{id}", name="new_from_request")
     * @param int $id
     * @return Response
     */
    public function createReservationFromRequest(int $id): Response
    {
        $request = $this->requestService->find($id);
        $reservationId = $this->reservationService->saveFromRequest($request);
        return $this->redirectToRoute('reservations_detail', ['id' => $reservationId]);
    }

    /**
     * @Route("/new/{id}", name="new")
     * @param int $id
     * @return Response
     */
    public function createReservation(int $id): Response
    {
        $user = $this->userService->find($id);
        if ($user->isAdmin() || $user->isRoomAdmin() || $user->isGroupAdmin()){
            if ($user->isAdmin())
                $rooms = $this->roomService->findAll();
            if ($user->isRoomAdmin()){
                $roomManager = $this->roomManagerService->find($user->getId());
                $rooms = $roomManager->getManagedRooms();
            }
            if ($user->isGroupAdmin()) {
                $groupManager = $this->groupManagerService->find($user->getId());
                $groups = $groupManager->getGroups();
                $rooms = $this->roomService->findByGroups($groups);
            }
            $form = $this->createForm(ReservationType::class, null, ['rooms' => $rooms ?? []])
                ->add('Reserve', SubmitType::class);

            return $this->render('reservations/new.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('permissions/denied.html.twig');
    }

}