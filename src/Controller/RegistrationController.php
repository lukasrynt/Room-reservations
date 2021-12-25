<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Services\UserService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private UserService $userService;

    /**
     * RegistrationController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @Route("/register", name="user_registration")
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