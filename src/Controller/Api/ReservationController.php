<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\ReservationService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $requests = $this->reservationService->filterAll($request->query->all());
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
        $request = $this->reservationService->find($id);
        $view = $this->view($request, Response::HTTP_OK);
        return $this->handleView($view);
    }
}