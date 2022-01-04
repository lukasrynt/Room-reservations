<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserSearchType;
use App\Form\Type\UserType;
use App\Services\ParamsParser;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        $this->denyAccessUnlessGranted('view_users', $user);
        return $this->render('users/detail.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     * @param Request $request
     * @param int $id
     * @param UserPasswordHasherInterface $passwordEncoder
     * @return Response
     */
    public function editUser(Request $request, int $id, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->userService->find($id);
        if (!$user) {
            return $this->render('errors/404.html.twig');
        }
        $this->denyAccessUnlessGranted('edit_user', $user);

        $form = $this->createForm(UserType::class, $user);

        if ($this->getUser()->isAdmin()){
            $form->add('roles', ChoiceType::class, [
                'choices' => User::getAllRoles(),
                'multiple' => true
            ]);
        }

        $form->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Save'
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $password = $passwordEncoder->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $this->userService->deleteRoomsOrGroups($user);
            $user->setRoles([$user->getRoles()[0]]);
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
        $this->denyAccessUnlessGranted('view_users');
        $searchForm = $this->createForm(UserSearchType::class);
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $count = $this->userService->countForParams($params);
        $users = $this->userService->filter($params);
        return $this->render('users/index.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm->createView(),
            'usersCount' => $count,
            'params' => $params
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response {
        $this->denyAccessUnlessGranted('view_users');
        $searchForm = $this->createForm(UserSearchType::class);
        $searchForm->handleRequest($request);
        $params = ParamsParser::getParamsFromUrl($request->query->all(), $searchForm->getData());
        $users = $this->userService->filter($params);
        $count = $this->userService->countForParams($params);
        return $this->render('users/index.html.twig', [
            'users' => $users,
            'searchForm' => $searchForm->createView(),
            'usersCount' => $count,
            'params' => $params
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