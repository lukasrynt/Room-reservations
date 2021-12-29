<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\Type\GroupType;
use App\Services\GroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groups", name="groups_")
 */
class GroupController extends AbstractController
{
    private GroupService $groupService;

    /**
     * GroupController constructor
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('view_groups');
        $groups = $this->groupService->findAll();
        return $this->render('groups/index.html.twig', ['groups' => $groups]);
    }

    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $group = $this->groupService->find($id);
        $this->denyAccessUnlessGranted('view_group', $group);
        if (!$group) {
            return $this->render('errors/404.html.twig');
        }

        return $this->render('groups/detail.html.twig', ['group' => $group]);
    }

    public function edit(Request $request) {

    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('create_group');
        $group = new Group;
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->groupService->save($form->getData());
            $this->addFlash('success', "Group {$group->getName()} was successfully created.");
            return $this->redirectToRoute('groups_detail', ['id' => $group->getId()]);
        }

        return $this->render('groups/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}