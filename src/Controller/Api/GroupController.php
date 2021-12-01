<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\GroupService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/groups", name="api_groups_")
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
     * @Rest\Get("/", name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $groups = $this->groupService->filter($request->query->all());
        $view = $this->view($groups, 200);
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{gid}/users/{uid}",
     *        name="add_user",
     *        requirements={"gid": "\d+", "uid": "\d+"})
     * @param int $gid
     * @param int $uid
     * @return Response
     */
    public function addUserToGroup(int $gid, int $uid): Response
    {
        $editedGroup = $this->groupService->addUser($gid, $uid);
        $view = $this->view($editedGroup, 201);
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/{gid}/users/{uid}",
     *        name="remove_user",
     *        requirements={"gid": "\d+", "uid": "\d+"})
     * @param int $gid
     * @param int $uid
     * @return Response
     */
    public function removeUserFromGroup(int $gid, int $uid): Response
    {
        $editedGroup = $this->groupService->removeUser($gid, $uid);
        $view = $this->view($editedGroup, 200);
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{gid}/rooms/{rid}",
     *        name="add_room",
     *        requirements={"gid": "\d+", "rid": "\d+"})
     * @param int $gid
     * @param int $rid
     * @return Response
     */
    public function addRoomToGroup(int $gid, int $rid): Response
    {
        $editedGroup = $this->groupService->addRoom($gid, $rid);
        $view = $this->view($editedGroup, 201);
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/{gid}/rooms/{rid}",
     *        name="remove_room",
     *        requirements={"gid": "\d+", "rid": "\d+"})
     * @param int $gid
     * @param int $rid
     * @return Response
     */
    public function removeRoomFromGroup(int $gid, int $rid): Response
    {
        $editedGroup = $this->groupService->removeRoom($gid, $rid);
        $view = $this->view($editedGroup, 200);
        return $this->handleView($view);
    }
}