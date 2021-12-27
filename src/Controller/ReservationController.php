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

    /**
     * ReservationController constructor.
     * @param ReservationService $reservationService
     * @param UserService $userService
     */
    public function __construct(ReservationService $reservationService, UserService $userService)
    {
        $this->reservationService = $reservationService;
        $this->userService = $userService;
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
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('reserve');
        $rooms = $this->userService->getRoomsForUser($user);
        $form = $this->createForm(ReservationType::class, null, ['rooms' => $rooms ?? []])
            ->add('Reserve', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $this->reservationService->save($reservation);
            $this->addFlash('success', "Reservation for room {$reservation->getRoom()->getName()} was successfully created.");
            return $this->redirectToRoute('reservations_detail', ['id' => $reservation->getId()]);
        }

        return $this->render('reservations/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}