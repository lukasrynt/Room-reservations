<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/users", name="api_users_")
 */
class UserController extends AbstractFOSRestController
{
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", methods={"GET"}, name="list")
     * @return Response
     */
    public function all(): Response
    {
        $users = $this->userRepository->findAll();
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
        $request = $this->userRepository->find($id);
        $view = $this->view($request, 200);
        return $this->handleView($view);
    }
}