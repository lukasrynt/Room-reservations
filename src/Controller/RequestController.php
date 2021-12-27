<?php

namespace App\Controller;

use App\Form\Type\RequestType;
use App\Services\RequestService;
use App\Services\RoomService;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * RequestController constructor.
     * @param RequestService $requestService
     * @param RoomService $roomService
     */
    public function __construct(RequestService $requestService, RoomService $roomService)
    {
        $this->requestService = $requestService;
        $this->roomService = $roomService;
    }

    /**
     * @Route("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->requestService->find($id);
        if (!$request)
            return $this->render('errors/404.html.twig');
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
        if (!$room)
            return $this->render('errors/404.html.twig');
        $this->denyAccessUnlessGranted('reserve', $room);
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
}