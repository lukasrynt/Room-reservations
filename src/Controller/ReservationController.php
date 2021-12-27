<?php


namespace App\Controller;


use App\Entity\Reservation;
use App\Form\Type\ReservationType;
use App\Services\RequestService;
use App\Services\ReservationService;
use App\Services\RoomService;
use App\Services\UserService;
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

    /**
     * ReservationController constructor.
     * @param ReservationService $reservationService
     * @param UserService $userService
     * @param RoomService $roomService
     */
    public function __construct(ReservationService $reservationService, UserService $userService, RoomService $roomService)
    {
        $this->reservationService = $reservationService;
        $this->userService = $userService;
        $this->roomService = $roomService;
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
     * @Route("/new/{id}", name="new")
     * @param int $id
     * @return Response
     */
    public function createReservation(int $id): Response
    {
        $user = $this->userService->find($id);

        if ($user->isAdmin())
            $rooms = $this->roomService->findAll();
        else if ($user->isRoomAdmin())
            $rooms = $this->userService->getManagedRoomsByRoomAdmin($user);
        else if ($user->isGroupAdmin())
            $rooms = $this->userService->getManagedRoomsByGroupAdmin($user);
        else
            return $this->render('permissions/denied.html.twig');

        $form = $this->createForm(ReservationType::class, null, ['rooms' => $rooms ?? []])
            ->add('Reserve', SubmitType::class);

        return $this->render('reservations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}