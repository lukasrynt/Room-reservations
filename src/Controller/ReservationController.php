<?php


namespace App\Controller;


use App\Entity\States;
use App\Form\Type\BookRoomType;
use App\Form\Type\ReservationType;
use App\Services\ParamsParser;
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
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $count = $this->reservationService->countForParamsAndUser($params, $this->getUser());
        $reservations = $this->reservationService->filterForUser($params, $this->getUser());
        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservations,
            'reservationsCount' => $count,
            'params' => $params
        ]);
    }

    /**
     * @Route("/{id}", name="detail", methods="GET", requirements={"id": "\d+"})
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
     * @Route("/{id}", name="delete", methods="DELETE", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        $this->denyAccessUnlessGranted('delete_reservation', $reservation);
        if (!$reservation) {
            return $this->render('errors/404.html.twig');
        } else {
            $this->reservationService->delete($reservation);
            $this->addFlash('success', "Reservation #{$id} for room {$reservation->getRoom()->getName()} was successfully deleted.");
            return $this->redirectToRoute('reservations_index');
        }
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('create_reservation');
        $rooms = $this->userService->getRoomsForUser($user);
        $form = $this->createForm(ReservationType::class, null, ['rooms' => $rooms ?? []])
            ->add('Reserve', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success']
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->reservationService->checkCollisionReservations($form->getData()))
            {
                $this->addFlash('danger', "Reservation in this time already exists!");
            } else {
                $reservation = $form->getData();
                $reservation->setState(new States("PENDING"));
                $this->reservationService->save($form->getData());
                $this->addFlash('success', "Reservation for room {$reservation->getRoom()->getName()} was successfully created.");
                return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
            }
        }

        return $this->render('reservations/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response{
        $reservation = $this->reservationService->find($id);

        if (!$reservation) {
            return $this->render('errors/404.html.twig');
        }

        $this->denyAccessUnlessGranted('edit_reservation', $reservation);
        $form = $this->createForm(BookRoomType::class, $reservation)
            ->add('Edit', SubmitType::class, [
                    'attr' => ['class' => 'button-base button-success']
                ]
            );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->reservationService->save($form->getData());
            $this->addFlash('success', "Reservation #{$reservation->getId()} was successfully edited.");
            return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
        }

        return $this->render('reservations/bookRoom.html.twig', [
            'form' => $form->createView(),
            'room' => $reservation->getRoom(),
            'user' => $reservation->getUser()
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
            if (!$this->reservationService->checkCollisionReservations($form->getData()))
            {
                $this->addFlash('danger', "Reservation at this time already exists!");
            } else {
                $this->reservationService->save($form->getData());
                $this->addFlash('success', "Booking request for room {$room->getName()} was successfully created. Please wait for administrator to submit it.");
                return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
            }
        }

        return $this->render('reservations/bookRoom.html.twig', [
            'room' => $room,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/approve", name="approve")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function approve(Request $request,int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        $this->denyAccessUnlessGranted('approve_reservation', $reservation);
        if (!$this->reservationService->checkCollisionReservations($reservation))
        {
            $this->addFlash('danger', "Reservation at this time already exists! Edit time of the reservation or reject it!");
            return $this->redirect($request->headers->get('referer'));
        }
        $reservation->setState(new States(States::APPROVED));
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
        $reservation->setState(new States(States::REJECTED));
        $this->reservationService->save($reservation);
        $this->addFlash('success', "Request with for room {$reservation->getRoom()->getName()} was successfully rejected.");
        return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
    }
}