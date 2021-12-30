<?php

namespace App\Controller;

use App\Entity\Building;
use App\Form\Type\BuildingType;
use App\Services\BuildingService;
use App\Services\ParamsParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/buildings", name="buildings_")
 */
class BuildingController extends AbstractController
{
    private BuildingService $buildingService;

    /**
     * BuildingController constructor.
     * @param BuildingService $buildingService
     */
    public function __construct(BuildingService $buildingService)
    {
        $this->buildingService = $buildingService;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('view_buildings');
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $count = $this->buildingService->countForParams($params);
        $buildings = $this->buildingService->filter($params);
        return $this->render('buildings/index.html.twig', [
            'buildings' => $buildings,
            'buildingsCount' => $count,
            'params' => $params
        ]);
    }

    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"}, methods="GET")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $building = $this->buildingService->find($id);
        if (!$building) {
            return $this->render('errors/404.html.twig');
        }
        $this->denyAccessUnlessGranted('view_building', $building);
        return $this->render('buildings/detail.html.twig', ['building' => $building]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $building = $this->buildingService->find($id);

        if (!$building) {
            return $this->render('errors/404.html.twig');
        }
        $this->denyAccessUnlessGranted('edit_building', $building);
        $form = $this->createForm(BuildingType::class, $building)
            ->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Edit'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->buildingService->save($form->getData());
            return $this->redirectToRoute('buildings_detail', ['id' => $building->getId()]);
        }

        return $this->render('buildings/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $this->denyAccessUnlessGranted('create_building');
        $building = new Building();
        $form = $this->createForm(BuildingType::class, $building) ->add('edit', SubmitType::class, [
            'attr' => ['class' => 'button-base button-success'],
            'label' => 'Create'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->buildingService->save($form->getData());
            $this->addFlash('success', "Building {$building->getName()} was successfully created.");
            return $this->redirectToRoute('buildings_detail', ['id' => $building->getId()]);
        }

        return $this->render('buildings/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $building = $this->buildingService->find($id);
        $this->denyAccessUnlessGranted('delete_building', $building);
        if (!$building) {
            return $this->render('errors/404.html.twig');
        } else {
            $this->buildingService->delete($building);
            $this->addFlash('success', "Building {$building->getName()} was successfully deleted.");
            return $this->redirectToRoute('buildings_index');
        }
    }
}