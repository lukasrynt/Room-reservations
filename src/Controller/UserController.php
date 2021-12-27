<?php

namespace App\Controller;

use App\Entity\RoomManager;
use App\Entity\User;
use App\Form\Type\LoginType;
use App\Form\Type\RequestType;
use App\Form\Type\UserSearchType;
use App\Form\Type\UserType;
use App\Repository\GroupManagerRepository;
use App\Repository\RoomManagerRepository;
use App\Services\GroupManagerService;
use App\Services\RequestService;
use App\Services\RoomManagerService;
use App\Services\RoomService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
    public function detail(int $id): Response
    {
        $user = $this->userService->find($id);
        if (!$user) {
            return $this->render('errors/404.html.twig');
        }
        return $this->render('users/detail.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function editUser(Request $request, int $id, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->userService->find($id);

        $form = $this->createForm(UserType::class, $user)
            ->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Save'
            ])
            ->add('delete', ButtonType::class, [
                'attr' => ['class' => 'button-base button-danger-outline'],
                'label' => 'Delete'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $password = $passwordEncoder->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $this->userService->save($user);
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
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $users = $this->userService->search($searchForm->getData());
        }
        return $this->render('users/index.html.twig', [
            'users' => $users ?? [],
            'searchForm' => $searchForm->createView(),
            'usersCount' => $users ? count($users) : 0
        ]);
    }

    /**
     * @Route("/register", name="registration")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user)
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'button-base button-main'],
                'label' => 'Register'
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($password);

            $this->userService->save($user);

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'users/signup.html.twig',
            array('form' => $form->createView())
        );
    }
}