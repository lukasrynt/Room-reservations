<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Form\Type\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * RoomController constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/users/{id}", name="user_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $user = $this->userRepository->find($id);
        if (!$user)
            return $this->render('errors/404.html.twig');
        return $this->render('users/detail.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/users/{id}/edit", name="edit_user")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editUser(Request $request, int $id) :Response
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($form->getData());
            $this->entityManager->flush();
            return $this->redirectToRoute('user_detail', ['id' => $user->getId()]);
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="users_index")
     * @return Response
     */
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('users/index.html.twig', ['users' => $users]);
    }

}