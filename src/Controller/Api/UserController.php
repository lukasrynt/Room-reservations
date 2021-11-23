<?php
/**
 * @author Lukas Rynt
 */

namespace App\Controller\Api;

use App\Repository\UserRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @package App\Controller\Api
 *
 * @Route("/api/users")
 * @Rest\View(serializerEnableMaxDepthChecks=true)
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
     * @Route("/", methods={"GET"})
     * @return Response
     */
    public function all(): Response
    {
        $users = $this->userRepository->findAll();
        $context = (new Context())->enableMaxDepth();
        $view = $this->view($users, 200)->setContext($context);
        return $this->handleView($view);
    }
}