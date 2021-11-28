<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
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
     * @Route("/", methods={"GET"}, name="list")
     * @param Request $request
     * @return Response
     */
    public function all(Request $request): Response
    {
        $users = $this->userService->filter($request->query->all());
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }

    /**
     * @Route("/{id}", name="detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $request = $this->userService->find($id);
        $view = $this->view($request, 200);
        return $this->handleView($view);
    }
}