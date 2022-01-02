<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Entity\States;
use App\Form\Type\ReservationRestType;
use App\Form\Type\ReservationType;
use App\Services\ParamsParser;
use App\Services\ReservationService;
use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/reservations", name="api_reservations_")
 */
class ReservationController extends AbstractFOSRestController
{
    private ReservationService $reservationService;
    private UserService $userService;

    /**
     * @param ReservationService $reservationService
     * @param UserService $userService
     */
    public function __construct(ReservationService $reservationService, UserService $userService)
    {
        $this->reservationService = $reservationService;
        $this->userService = $userService;
    }


    /**
     * @Rest\Get("/",  name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $reservations = $this->reservationService->filter($params);
        $view = $this->view($reservations, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($reservation, Response::HTTP_OK);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/{id}", name="delete", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $this->reservationService->delete($reservation);
            $view = $this->view($reservation, Response::HTTP_OK);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $view = $this->handleFormSubmission($request, new Reservation(), Response::HTTP_CREATED);
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{id}", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->handleFormSubmission($request, $reservation, Response::HTTP_OK);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{id}/approve", name="approve", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function approve(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            return $this->handleView($this->view([], Response::HTTP_NOT_FOUND));
        }
        if (!$reservation->isPending()) {
            return $this->handleView($this->view('Reservation must be pending to be approved', Response::HTTP_FORBIDDEN));
        }
        if (!$this->reservationService->checkCollisionReservations($reservation)) {
            return $this->handleView($this->view("Reservation at this time already exists", Response::HTTP_BAD_REQUEST));
        }
        $reservation->setState(new States(States::APPROVED));
        $this->reservationService->save($reservation);
        return $this->handleView($this->view($reservation, Response::HTTP_OK));
    }

    /**
     * @Rest\Put("/{id}/reject", name="reject", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function reject(int $id): Response
    {
        $reservation = $this->reservationService->find($id);
        if (!$reservation) {
            return $this->handleView($this->view([], Response::HTTP_NOT_FOUND));
        }
        if (!$reservation->isPending()) {
            return $this->handleView($this->view('Reservation must be pending to be rejected', Response::HTTP_FORBIDDEN));
        }
        $reservation->setState(new States(States::REJECTED));
        $this->reservationService->save($reservation);
        return $this->handleView($this->view($reservation, Response::HTTP_OK));
    }

    /**
     * @Rest\Put("/{rid}/attendees/{uid}", name="add_attendee", requirements={"rid": "\d+", "uid": "\d+"})
     * @param int $rid reservation ID
     * @param int $uid user ID
     * @return Response
     */
    public function addAttendee(int $rid, int $uid): Response
    {
        $reservation = $this->reservationService->find($rid);
        $user = $this->userService->find($uid);
        if (!$reservation || !$user) {
            return $this->handleView($this->view([], Response::HTTP_NOT_FOUND));
        }
        if (!$reservation->isPending()) {
            return $this->handleView($this->view('Reservation must be pending to accept any more attendees', Response::HTTP_FORBIDDEN));
        }
        $reservation->addAttendee($user);
        $this->reservationService->save($reservation);
        return $this->handleView($this->view($reservation, Response::HTTP_OK));
    }

    /**
     * @Rest\Delete("/{rid}/attendees/{uid}", name="remove_attendee", requirements={"rid": "\d+", "uid": "\d+"})
     * @param int $rid reservation ID
     * @param int $uid user ID
     * @return Response
     */
    public function removeAttendee(int $rid, int $uid): Response
    {
        $reservation = $this->reservationService->find($rid);
        $user = $this->userService->find($uid);
        if (!$reservation || !$user) {
            return $this->handleView($this->view([], Response::HTTP_NOT_FOUND));
        }
        if (!$reservation->isPending()) {
            return $this->handleView($this->view('Reservation must be pending to remove any attendees', Response::HTTP_FORBIDDEN));
        }
        $reservation->removeAttendee($user);
        $this->reservationService->save($reservation);
        return $this->handleView($this->view($reservation, Response::HTTP_OK));
    }

    private function handleFormSubmission(Request $request, Reservation $reservation, int $statusOk): View
    {
        $form = $this->createForm(ReservationRestType::class, $reservation, ['csrf_protection' => false]);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $this->view($form->getData(), Response::HTTP_BAD_REQUEST);
        }
        if (!$this->reservationService->checkCollisionReservations($form->getData())){
            return $this->view("Reservation at this time already exists", Response::HTTP_BAD_REQUEST);
        }
        $reservation = $form->getData();
        $this->reservationService->save($reservation);
        return $this->view($reservation, $statusOk);
    }
}