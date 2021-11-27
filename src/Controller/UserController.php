<?php

namespace App\Controller;

use App\Form\Type\UserSearchType;
use App\Form\Type\UserType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{

    private UserService $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $user = $this->userService->find($id);
        if (!$user)
            return $this->render('errors/404.html.twig');
        return $this->render('users/detail.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editUser(Request $request, int $id) :Response
    {
        $user = $this->userService->find($id);

        $form = $this->createForm(UserType::class, $user)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->userService->save($form->getData());
            return $this->redirectToRoute('users_detail', ['id' => $user->getId()]);
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $searchForm = $this->createForm(UserSearchType::class);
        $count = count($this->userService->findAll());
        $users = $this->userService->filter($request->query->all());
        return $this->render('users/index.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm->createView(),
            'usersCount' => $count
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response {
        $searchForm = $this->createForm(UserSearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid())
            $users = $this->userService->search($searchForm->getData());
        return $this->render('users/index.html.twig', [
            'users' => $users ?? [],
            'searchForm' => $searchForm->createView(),
            'usersCount' => $users ? count($users) : 0
        ]);
    }
}