<?php


namespace App\Controller;


use App\Entity\Room;
use App\Entity\User;
use App\Form\Type\NameRoomManagerType;
use App\Form\Type\RoomSearchType;
use App\Services\ParamsParser;
use App\Services\RoomService;
use App\Form\Type\RoomType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rooms", name="rooms_")
 */
class RoomController extends AbstractController
{
    private RoomService $roomService;
    private UserService $userService;

    /**
     * RoomController constructor.
     * @param RoomService $roomService
     * @param UserService $userService
     */
    public function __construct(RoomService $roomService, UserService $userService)
    {
        $this->roomService = $roomService;
        $this->userService = $userService;
    }


    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('view_rooms');
        $searchForm = $this->createForm(RoomSearchType::class);
        $searchForm->handleRequest($request);
        $params = ParamsParser::getParamsFromUrl($request->query->all());
        $count = $this->roomService->countForParamsAndUser($params, $this->getUser());
        $rooms = $this->roomService->filterForUser($params, $this->getUser());
        return $this->render('rooms/index.html.twig', [
            'rooms' => $rooms,
            'searchForm' => $searchForm->createView(),
            'params' => $params,
            'roomsCount' => $count
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response {
        $this->denyAccessUnlessGranted('view_rooms');
        $searchForm = $this->createForm(RoomSearchType::class);
        $searchForm->handleRequest($request);
        $params = ParamsParser::getParamsFromUrl($request->query->all(), $searchForm->getData());
        $rooms = $this->roomService->filterForUser($params, $this->getUser());
        $count = $this->roomService->countForParamsAndUser($params, $this->getUser());
        return $this->render('rooms/index.html.twig', [
            'rooms' => $rooms,
            'searchForm' => $searchForm->createView(),
            'params' => $params,
            'roomsCount' => $count
        ]);
    }

    /**
     * @Route("/{id}", name="detail", methods="GET", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response{
        $room = $this->roomService->find($id);
        $this->denyAccessUnlessGranted('view_room', $room);
        if (!$room) {
            return $this->render('errors/404.html.twig');
        }
        return $this->render('rooms/detail.html.twig', ['room' => $room]);
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response{
        $room = $this->roomService->find($id);

        if (!$room) {
            return $this->render('errors/404.html.twig');
        }

        $this->denyAccessUnlessGranted('edit_room', $room);
        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            $this->addFlash('success', "Room {$room->getName()} was successfully edited.");
            return $this->redirectToRoute('rooms_detail', ['id' => $room->getId()]);
        } else {
            $this->displayErrorMessages($form->getErrors());
        }

        return $this->render('rooms/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function displayErrorMessages($errors)
    {
        foreach ($errors as $key => $error) {
            $this->addFlash('danger', $error->getMessage());
        }
    }

    /**
     * @Route("/{id}", name="delete", methods="DELETE", requirements={"id": "\d+"})
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $room = $this->roomService->find($id);
        $this->denyAccessUnlessGranted('delete_room', $room);
        if (!$room) {
            return $this->render('errors/404.html.twig');
        } else {
            $this->roomService->delete($room);
            $this->addFlash('success', "Room {$room->getName()} was successfully deleted along with all reservations for it.");
            return $this->redirectToRoute('rooms_index');
        }
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response {
        $this->denyAccessUnlessGranted('create_room');
        $room = new Room;
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            $this->addFlash('success', "Room {$room->getName()} was successfully created.");
            return $this->redirectToRoute('rooms_detail', ['id' => $room->getId()]);
        } else {
            $this->displayErrorMessages($form->getErrors());
        }

        return $this->render('rooms/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/name_manager", name="name_manager", requirements={"id": "\d+"})
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function nameManager(Request $request, int $id): Response
    {
        $room = $this->roomService->find($id);

        if (!$room) {
            return $this->render('errors/404.html.twig');
        }
        $this->denyAccessUnlessGranted('name_room_manager', $room);

        $form = $this->createForm(NameRoomManagerType::class, $room);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->roomService->save($form->getData());
            $user = $room->getRoomManager();
            if (!$user->isGroupAdmin() && !$user->isAdmin()) {
                $user->setRoles([User::ROOM_ADMIN]);
            }
            $this->userService->save($user);
            $this->roomService->save($room);
            $this->addFlash('success', "User {$user->getUsername()} was successfully appointed as room manager for room {$room->getName()}.");
            return $this->redirectToRoute('rooms_detail', ['id' => $room->getId()]);
        }

        return $this->render('rooms/nameRoomManager.html.twig', [
            'form' => $form->createView()
        ]);
    }
}