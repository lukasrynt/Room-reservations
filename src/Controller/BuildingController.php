<?php


namespace App\Controller;



use App\Form\Type\BuildingType;
use App\Repository\BuildingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends AbstractController
{
    private BuildingRepository $buildingRepository;
    private EntityManagerInterface $entityManager;

    /**
     * BuildingController constructor.
     * @param BuildingRepository $buildingRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(BuildingRepository $buildingRepository, EntityManagerInterface $entityManager)
    {
        $this->buildingRepository = $buildingRepository;
        $this->entityManager = $entityManager;
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

    /**
     * @Route("/buildings/{id}", name="building_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $building = $this->buildingRepository->find($id);
        if (!$building)
            return $this->render('errors/404.html.twig');
        return $this->render('buildings/detail.html.twig', ['building' => $building]);
    }

    /**
     * @Route("/buildings/{id}/edit", name="building_edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response{
        $building = $this->buildingRepository->find($id);

        if (!$building)
            return $this->render('errors/404.html.twig');

        $form = $this->createForm(BuildingType::class, $building)
            ->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Save'
            ])
            ->add('delete', ButtonType::class, [
                'attr' => ['class' => 'button-base button-danger-outline'],
                'label' => 'Delete'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            return $this->redirectToRoute('building_detail', ['id' => $building->getId()]);
        }

        return $this->render('buildings/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}