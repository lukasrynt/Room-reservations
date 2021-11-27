<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Form\Type\RequestType;
use App\Form\Type\UserSearchType;
use App\Form\Type\UserType;
use App\Services\RequestService;
use App\Services\RoomService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="users_")
 */
class UserController extends AbstractController
{

    private UserService $userService;
    private RoomService $roomService;
    private RequestService $requestService;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param RoomService $roomService
     */
    public function __construct(UserService $userService, RoomService $roomService)
    {
        $this->userService = $userService;
        $this->roomService = $roomService;
    }


    /**
     * @Route("/{id}", name="detail", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
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
    public function editUser(Request $request, int $id): Response
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
     * @Route("/users/{user_id}/book_room/{room_id}", name="users_book_room_member")
     * @param Request $request
     * @param int $user_id
     * @param int $room_id
     * @return Response
     */
    public function bookRoomAsMember(Request $request, int $user_id, int $room_id): Response
    {
        $user = $this->userService->find($user_id);
        $room = $this->roomService->find($room_id);

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
                $this->requestService->save($form->getData());
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
        $user = $this->userService->find($id);
        if ($user->isAdmin()) {
            $requests = $this->requestService->findNotApprovedRequestsAll();
        } elseif ($user->isRoomAdmin()) {
            $requests = $this->requestService->findNotApprovedRequestsByRoom();
        } elseif ($user->isGroupAdmin()) {
            $requests = $this->requestService->findNotApprovedRequestsByGroup();
        } else {
            return $this->render('permissions/denied.html.twig');
        }
        return $this->render('users/confirm_requests.html.twig', ['requests' => $requests]);
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