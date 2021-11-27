<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Form\Type\RequestType;
use App\Form\Type\ReservationType;
use App\Form\Type\UserType;
use App\Repository\RequestRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private RequestRepository $requestRepository;
    private RoomRepository $roomRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param RequestRepository $requestRepository
     * @param RoomRepository $roomRepository
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, RequestRepository $requestRepository, RoomRepository $roomRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->requestRepository = $requestRepository;
        $this->roomRepository = $roomRepository;
    }


    /**
     * @Route("/users/{id}", name="user_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
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
    public function editUser(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user)
            ->add('edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/users/{user_id}/book_room/{room_id}", name="users_book_room_member")
     * @param Request $request
     * @param int $user_id
     * @param int $room_id
     * @return Response
     */
    public function bookRoomAsMember(Request $request, int $user_id, int $room_id): Response
    {
        $user = $this->userRepository->find($user_id);
        $room = $this->roomRepository->find($room_id);

        //todo roomAdmin + groupADmin + jiné uživatele tam dělat rovnou reservation

        if ($user->isRoomMember() || $user->isAdmin() || $user->isRoomAdmin()) {
            $newRequest = new \App\Entity\Request();
            $newRequest->setRequestor($user);
            $newRequest->setValid(false);
            $newRequest->setRoom($room);

            /*if ($user->isRoomMember()){
                $rooms = $user->getRooms();
            }elseif($user->isGroupMember()){
                // todo
                $rooms = $user->getRooms();
            }else
                $rooms = [];*/

            $form = $this->createForm(RequestType::class, $newRequest)
                ->add('Request', SubmitType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($form->getData());
                $this->entityManager->flush();
                return $this->redirectToRoute('request_detail', ['id' => $newRequest->getId()]);
            }

            return $this->render('requests/new.html.twig', [
                'room' => $room,
                'user' => $user,
                'form' => $form->createView()
            ]);
        } else {
            return $this->render('permissions/denied.html.twig');
        }
    }

    /**
     * @Route("/users/{id}/confirm_requests", name="users_confirm_requests")
     * @param int $id
     * @return Response
     */
    public function confirmRequest(int $id)
    {
        $user = $this->userRepository->find($id);
        if ($user->isAdmin()) {
            $requests = $this->requestRepository->findNotApprovedRequestsAll();
        } elseif ($user->isRoomAdmin()) {
            $requests = $this->requestRepository->findNotApprovedRequestsByRoom();
        } elseif ($user->isGroupAdmin()) {
            $requests = $this->requestRepository->findNotApprovedRequestsByGroup();
        } else {
            return $this->render('permissions/denied.html.twig');
        }
        return $this->render('users/confirm_requests.html.twig', ['requests' => $requests]);
    }

}