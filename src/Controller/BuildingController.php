<?php


namespace App\Controller;



use App\Repository\BuildingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends AbstractController
{
    private BuildingRepository $buildingRepository;

    /**
     * BuildingController constructor.
     * @param BuildingRepository $buildingRepository
     */
    public function __construct(BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    /**
     * @Route("/buildings", name="buildings_index")
     * @return Response
     */
    public function index(): Response
    {
        $buildings = $this->buildingRepository->findAll();
        return $this->render('buildings/index.html.twig', ['buildings' => $buildings]);
    }
}