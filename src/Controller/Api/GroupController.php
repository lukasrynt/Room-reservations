<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\GroupService;
use App\Services\ParamsParser;
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
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $groups = $this->groupService->filter($params);
        $view = $this->view($groups, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $group = $this->groupService->find($id);
        if (!$group) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($group, Response::HTTP_OK);
        }
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
        if (!$editedGroup) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($editedGroup, Response::HTTP_CREATED);
        }
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
        if (!$editedGroup) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($editedGroup, Response::HTTP_OK);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/{gid}/rooms/{rid}",
     *        name="add_room",
     *        methods="PUT",
     *        requirements={"gid": "\d+", "rid": "\d+"})
     * @param int $gid
     * @param int $rid
     * @return Response
     */
    public function addRoomToGroup(int $gid, int $rid): Response
    {
        $editedGroup = $this->groupService->addRoom($gid, $rid);
        if (!$editedGroup) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($editedGroup, Response::HTTP_CREATED);
        }
        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/{gid}/rooms/{rid}",
     *        name="remove_room",
     *        methods="DELETE",
     *        requirements={"gid": "\d+", "rid": "\d+"})
     * @param int $gid
     * @param int $rid
     * @return Response
     */
    public function removeRoomFromGroup(int $gid, int $rid): Response
    {
        $editedGroup = $this->groupService->removeRoom($gid, $rid);
        if (!$editedGroup) {
            $view = $this->view([], Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($editedGroup, Response::HTTP_OK);
        }
        return $this->handleView($view);
    }
}