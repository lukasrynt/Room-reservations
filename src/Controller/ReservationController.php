<?php


namespace App\Controller;


use App\Entity\States;
use App\Form\Type\BookRoomType;
use App\Form\Type\ReservationType;
use App\Services\ReservationService;
use App\Services\RoomService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('view_reservations');
        $reservations = $this->reservationService->filterAllForUser($this->getUser(), $request->query->all());
        return $this->render('reservations/index.html.twig', ['reservations' => $reservations]);
    }

    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            return $this->render('errors/404.html.twig');
        }
        return $this->render('reservations/detail.html.twig', [
            'reservation' => $reservation
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        # TODO: auto approve requests if logged in as admin of the room/group/sysadmin - should be done using voters
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('create_reservation');
        $rooms = $this->userService->getRoomsForUser($user);
        $form = $this->createForm(ReservationType::class, null, ['rooms' => $rooms ?? []])
            ->add('Reserve', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success']
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $reservation->setState(new States("PENDING"));
            $this->reservationService->save($form->getData());
            $this->addFlash('success', "Reservation for room {$reservation->getRoom()->getName()} was successfully created.");
            return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
        }

        return $this->render('reservations/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/book_room/{roomId}", name="book_room")
     * @param Request $request
     * @param int $roomId
     * @return Response
     */
    public function bookRoom(Request $request, int $roomId): Response
    {
        # TODO: auto approve requests if logged in as admin of the room/group/sysadmin - should be done using voters
        $user = $this->getUser();
        $room = $this->roomService->find($roomId);
        if (!$room) {
            return $this->render('errors/404.html.twig');
        }
        $this->denyAccessUnlessGranted('book_room', $room);
        $reservation = $this->reservationService->newWithRequesterAndRoom($user, $room);

        $form = $this->createForm(BookRoomType::class, $reservation)
            ->add('Reserve', SubmitType::class, [
                    'attr' => ['class' => 'button-base button-success']
                ]
            );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->reservationService->save($form->getData());
            $this->addFlash('success', "Booking request for room {$room->getName()} was successfully created. Please wait for administrator to submit it.");
            return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
        }

        return $this->render('reservations/bookRoom.html.twig', [
            'room' => $room,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/approve", name="approve")
     * @param int $id
     * @return Response
     */
    public function approve(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        $this->denyAccessUnlessGranted('approve_reservation', $reservation);
        $reservation->setState(new States('APPROVED'));
        $this->reservationService->save($reservation);
        $this->addFlash('success', "Reservation for room {$reservation->getRoom()->getName()} was successfully approved.");
        return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
    }

    /**
     * @Route("/{id}/reject", name="reject")
     * @param int $id
     * @return Response
     */
    public function reject(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        $this->denyAccessUnlessGranted('approve_reservation', $reservation);
        $reservation->setState(new States('REJECTED'));
        $this->reservationService->save($reservation);
        $this->addFlash('success', "Request with for room {$reservation->getRoom()->getName()} was successfully rejected.");
        return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
    }
}