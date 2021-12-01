<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\RequestRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * @Rest\Get("/",  name="list")
     * @return Response
     */
    public function all(): Response
    {
        $requests = $this->requestRepository->findAll();
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
        $request = $this->requestRepository->find($id);
        $view = $this->view($request, Response::HTTP_OK);
        return $this->handleView($view);
    }
}