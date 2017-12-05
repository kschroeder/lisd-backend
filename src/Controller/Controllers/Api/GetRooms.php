<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\Repositories\RoomSubscription\RoomSubscriptionRepository;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class GetRooms extends AbstractController
{

    private $roomRepository;
    private $authentication;
    private $roomSubscriptionRepository;

    public function __construct(
        RoomRepository $roomRepository,
        AuthorizationInterface $authorization,
        RoomSubscriptionRepository $roomSubscriptionRepository
    )
    {
        $this->roomRepository = $roomRepository;
        $this->authentication = $authorization;
        $this->roomSubscriptionRepository = $roomSubscriptionRepository;
    }

    public function execute(): ResponseInterface
    {
        $results = [];
        $rooms = $this->roomSubscriptionRepository->loadByAccount($this->authentication->getAccount());
        foreach ($rooms as $room) {

            $roomInstance = $this->roomRepository->loadById($room->getRoomId());
            $results[] = [
                'id' => (string)$roomInstance->getId(),
                'name' => $roomInstance->getName(),
                'created_at' => $roomInstance->getCreatedAt()->toDateTime()->getTimestamp()
            ];
        }
        return (new SuccessfulApiResponse())->getResponse($results);

    }

}
