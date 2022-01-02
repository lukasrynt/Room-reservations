<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\ParamsParser;
use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use PhpParser\Node\Param;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractFOSRestController
{
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Get("/", name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $params = ParamsParser::getParamsFromUrl($request->query->all(), null, false);
        $users = $this->userService->filter($params);
        $view = $this->view($users, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->userService->find($id);
        $view = $this->view($request, Response::HTTP_OK);
        return $this->handleView($view);
    }
}