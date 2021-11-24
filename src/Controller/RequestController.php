<?php

namespace App\Controller;

use App\Services\RequestService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/requests", name="requests_")
 */
class RequestController extends AbstractController
{
    private RequestService $requestService;

    /**
     * RequestController constructor.
     * @param RequestService $requestService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestService $requestService, EntityManagerInterface $entityManager)
    {
        $this->requestService = $requestService;
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
}