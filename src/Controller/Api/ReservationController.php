<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\ReservationRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/reservations", name="api_reservations_")
 */
class ReservationController extends AbstractFOSRestController
{
    private ReservationRepository $reservationRepository;

    /**
     * @param ReservationRepository $reservationRepository
     */
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }


    /**
     * @Rest\Get("/",  name="list")
     * @return Response
     */
    public function all(): Response
    {
        $requests = $this->reservationRepository->findAll();
        $view = $this->view($requests, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->reservationRepository->find($id);
        $view = $this->view($request, Response::HTTP_OK);
        return $this->handleView($view);
    }
}