<?php

namespace App\Controller;

use App\Entity\States;
use App\Form\Type\RequestType;
use App\Services\RequestService;
use App\Services\ReservationService;
use App\Services\RoomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/requests", name="requests_")
 */
class RequestController extends AbstractController
{
    private RequestService $requestService;
    private RoomService $roomService;
    private ReservationService $reservationService;

    /**
     * RequestController constructor.
     * @param RequestService $requestService
     * @param RoomService $roomService
     * @param ReservationService $reservationService
     */
    public function __construct(RequestService $requestService,
                                RoomService $roomService,
                                ReservationService $reservationService)
    {
        $this->requestService = $requestService;
        $this->roomService = $roomService;
        $this->reservationService = $reservationService;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $requests = $this->requestService->findAllFor($this->getUser());
        return $this->render('requests/index.html.twig', ['requests' => $requests]);
    }

    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->requestService->find($id);
        if (!$request) {
            return $this->render('errors/404.html.twig');
        }
        return $this->render('requests/detail.html.twig', ['request' => $request]);

    }

    /**
     * @Route("/book_room_member/{roomId}", name="book_room")
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
        $this->denyAccessUnlessGranted('book', $room);
        $newRequest = $this->requestService->newWithRequestorAndRoom($user, $room);

        $form = $this->createForm(RequestType::class, $newRequest)
            ->add('Request', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->requestService->save($form->getData());
            $this->addFlash('success', "Booking request for room {$room->getName()} was successfully created. Please wait for administrator to submit it.");
            return $this->redirectToRoute('requests_detail', ['id' => $newRequest->getId()]);
        }

        return $this->render('requests/new.html.twig', [
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
        $request = $this->requestService->find($id);
        $this->denyAccessUnlessGranted('approve', $request);
        $request->setState(new States('APPROVED'));
        $this->requestService->save($request);
        $reservationId = $this->reservationService->saveFromRequest($request);
        $this->addFlash('success', "Reservation for room {$request->getRoom()->getName()} was successfully created from request #{$request->getId()}.");
        return $this->redirectToRoute('reservations_detail', ['id' => $reservationId]);
    }

    /**
     * @Route("/{id}/reject", name="reject")
     * @param int $id
     * @return Response
     */
    public function reject(int $id): Response
    {
        $request = $this->requestService->find($id);
        $this->denyAccessUnlessGranted('approve', $request);
        $request->setState(new States('REJECTED'));
        $this->requestService->save($request);
        $this->addFlash('success', "Request with id #{$request->getId()} for room {$request->getRoom()->getName()} was successfully rejected.");
        return $this->redirectToRoute('requests_index');
    }
}