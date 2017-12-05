<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class GetRooms extends AbstractController
{

    private $roomRepository;
    private $authentication;

    public function __construct(
        RoomRepository $roomRepository,
        AuthorizationInterface $authorization
    )
    {
        $this->roomRepository = $roomRepository;
        $this->authentication = $authorization;
    }

    public function execute(): ResponseInterface
    {
        $results = [];
        $rooms = $this->roomRepository->loadByAccount($this->authentication->getAccount());
        foreach ($rooms as $room) {
            $results[] = [
                'id' => (string)$room->getId(),
                'name' => $room->getName(),
                'created_at' => $room->getCreatedAt()->toDateTime()->getTimestamp()
            ];
        }
        return (new SuccessfulApiResponse())->getResponse($results);

    }

}
