<?php

namespace App\Controller;

use App\Entity\RoomManager;
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
    private RoomManagerService $roomManagerService;
    private GroupManagerService $groupManagerService;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param RoomService $roomService
     * @param RequestService $requestService
     * @param RoomManagerService $roomManagerService
     * @param GroupManagerService $groupManagerService
     */
    public function __construct(UserService $userService, RoomService $roomService, RequestService $requestService, RoomManagerService $roomManagerService, GroupManagerService $groupManagerService)
    {
        $this->userService = $userService;
        $this->roomService = $roomService;
        $this->requestService = $requestService;
        $this->roomManagerService = $roomManagerService;
        $this->groupManagerService = $groupManagerService;
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
     * @Route("/{user_id}/book_room_member/{room_id}", name="book_room_member")
     * @param Request $request
     * @param int $user_id
     * @param int $room_id
     * @return Response
     */
    # TODO; move to request controller once we have authorization setup
    public function bookRoomAsMember(Request $request, int $user_id, int $room_id): Response
    {
        $user = $this->userService->find($user_id);
        $room = $this->roomService->find($room_id);

        if (in_array($room, $user->getRooms()->getValues()) || $user->getGroup() === $room->getGroup()) {

            $newRequest = $this->requestService->newWithRequestorAndRoom($user, $room);

            $form = $this->createForm(RequestType::class, $newRequest)
                ->add('Request', SubmitType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->requestService->save($form->getData());
                return $this->redirectToRoute('requests_detail', ['id' => $newRequest->getId()]);
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
     * @Route("/{id}/confirm_requests", name="confirm_requests")
     * @param int $id
     * @return Response
     */
    # TODO; move to request controller once we have authorization setup
    public function confirmRequest(int $id)
    {
        $user = $this->userService->find($id);
        if ($user->isCommonUser())
            return $this->render('permissions/denied.html.twig');
        $requests = $this->requestService->getRequestsToConfirmFor($user);
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