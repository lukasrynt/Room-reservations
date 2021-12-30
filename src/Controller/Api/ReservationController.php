<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Entity\States;
use App\Form\Type\ReservationRestType;
use App\Services\ReservationService;
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

    /**
     * @param ReservationService $reservationService
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }


    /**
     * @Rest\Get("/",  name="list")
     * @param Request $reservation
     * @return Response
     */
    public function all(Request $reservation): Response
    {
        $reservations = $this->reservationService->filterAll($reservation->query->all());
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

    private function handleFormSubmission(Request $request, Reservation $reservation, int $statusOk): View
    {
        $form = $this->createForm(ReservationRestType::class, $reservation, ['csrf_protection' => false]);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $this->view($form->getData());
        }

        $reservation = $form->getData();
        $this->reservationService->save($reservation);
        return $this->view($reservation, $statusOk);
    }
}