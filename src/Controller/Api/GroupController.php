<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/groups", name="_api_groups")
 */
class GroupController extends AbstractFOSRestController
{
    private GroupService $groupService;

    /**
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $groups = $this->groupService->filter($request->query->all());
        $view = $this->view($groups, 200);
        return $this->handleView($view);
    }
}