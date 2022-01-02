<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\Type\GroupSearchType;
use App\Form\Type\GroupType;
use App\Services\GroupService;
use App\Services\ParamsParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
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
        $searchForm = $this->createForm(GroupSearchType::class);
        $searchForm->handleRequest($request);
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $count = $this->groupService->countForParams($params);
        $groups = $this->groupService->filter($params);
        return $this->render('groups/index.html.twig', [
            'groups' => $groups,
            'searchForm' => $searchForm->createView(),
            'groupsCount' => $count,
            'params' => $params
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response {
        $this->denyAccessUnlessGranted('view_groups');
        $searchForm = $this->createForm(GroupSearchType::class);
        $searchForm->handleRequest($request);
        $params = ParamsParser::getParamsFromUrl($request->query->all(), $searchForm->getData());
        $groups = $this->groupService->filter($params);
        $count = $this->groupService->countForParams($params);
        return $this->render('groups/index.html.twig', [
            'groups' => $groups,
            'searchForm' => $searchForm->createView(),
            'groupsCount' => $count,
            'params' => $params
        ]);
    }

    /**
     * @Route("/{id}", name="detail", methods="GET", requirements={"id": "\d+"})
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

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $group = $this->groupService->find($id);
        if (!$group) {
            return $this->render('errors/404.html.twig');
        }

        $this->denyAccessUnlessGranted('edit_group', $group);
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->groupService->save($form->getData());
            $this->addFlash('success', "Group {$group->getName()} was successfully edited.");
            return $this->redirectToRoute('groups_detail', ['id' => $group->getId()]);
        }

        return $this->render('groups/edit.html.twig', [
            'form' => $form->createView()
        ]);

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

    /**
     * @Route("/{id}", name="delete", methods="DELETE", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $group = $this->groupService->find($id);
        $this->denyAccessUnlessGranted('delete_group');
        if (!$group) {
            return $this->render('errors/404.html.twig');
        } else {
            $this->groupService->delete($group);
            $this->addFlash('success', "Group {$group->getName()} was successfully deleted.");
            return $this->redirectToRoute('groups_index');
        }
    }

}