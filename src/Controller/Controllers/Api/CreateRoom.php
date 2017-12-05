<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\Controllers\Api\InputFilter\Message;
use Lisd\Controller\RequestToJson;
use Lisd\Processing\Manager\ProcessManager;
use Lisd\Processing\Processor\MessageNotifications;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class CreateRoom extends AbstractController
{

    private $roomRepository;
    private $authorization;
    private $createRoomFilter;
    private $jsonRequest;

    public function __construct(
        RoomRepository $roomRepository,
        AuthorizationInterface $authorization,
        RequestToJson $requestToJson,
        \Lisd\Controller\Controllers\Api\InputFilter\CreateRoom $createRoom
    )
    {
        $this->roomRepository = $roomRepository;
        $this->authorization = $authorization;
        $this->jsonRequest = $requestToJson;
        $this->createRoomFilter = $createRoom;
    }

    public function execute(): ResponseInterface
    {
        $request = $this->jsonRequest->json();
        $this->createRoomFilter->init();
        $this->createRoomFilter->setData($request);
        if ($this->createRoomFilter->isValid()) {
            $room = new Room();
            $room->setName($this->createRoomFilter->getValue('name'));
            $room->setOwner($this->authorization->getAccount());
            $objectId = $this->roomRepository->save($room)->getInsertedId();
            $room = $this->roomRepository->loadById($objectId);

            return (new SuccessfulApiResponse())->getResponse([
                'id' => (string)$room->getId(),
                'name' => $room->getName(),
                'created_at' => $room->getCreatedAt()->toDateTime()->getTimestamp()
            ]);
        }
        return (new FailedApiResponse())->getResponse($this->createRoomFilter->getMessages());
    }

}
