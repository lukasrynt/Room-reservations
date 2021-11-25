<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\RequestRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/requests", name="api_requests_")
 */
class RequestController extends AbstractFOSRestController
{
    private RequestRepository $requestRepository;

    /**
     * @param RequestRepository $requestRepository
     */
    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }


    /**
     * @Route("/", methods={"GET"}, name="list")
     * @return Response
     */
    public function all(): Response
    {
        $requests = $this->requestRepository->findAll();
        $view = $this->view($requests, 200);
        return $this->handleView($view);
    }

    /**
     * @Route("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->requestRepository->find($id);
        $view = $this->view($request, 200);
        return $this->handleView($view);
    }
}