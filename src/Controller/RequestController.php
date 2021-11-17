<?php


namespace App\Controller;


use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    private RequestRepository $requestRepository;
    private EntityManagerInterface $entityManager;

    /**
     * RequestController constructor.
     * @param RequestRepository $requestRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RequestRepository $requestRepository, EntityManagerInterface $entityManager)
    {
        $this->requestRepository = $requestRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/requests/{id}", name="request_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id){
        $request = $this->requestRepository->find($id);
        if (!$request)
            return $this->render('errors/404.html.twig');
        return $this->render('requests/detail.html.twig', ['request' => $request]);

    }

}