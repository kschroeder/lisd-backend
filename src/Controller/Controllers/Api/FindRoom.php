<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\RequestToJson;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\Repositories\RoomSubscription\RoomSubscriptionRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use MongoDB\BSON\Regex;
use Psr\Http\Message\ResponseInterface;

class FindRoom extends AbstractController
{

    private $roomRepository;
    private $request;
    private $authorization;
    private $roomSubscriptionRepository;

    public function __construct(
        AuthorizationInterface $authorization,
        RequestToJson $requestToJson,
        RoomRepository $roomRepository,
        RoomSubscriptionRepository $roomSubscriptionRepository
    )
    {
        $this->request = $requestToJson;
        $this->authorization = $authorization;
        $this->roomRepository = $roomRepository;
        $this->roomSubscriptionRepository = $roomSubscriptionRepository;
    }

    public function execute(): ResponseInterface
    {
        $query = $this->request->json();
        if (!isset($query['search'])) {
            return (new FailedApiResponse())->getResponse('Missing search text');
        }
        $rooms = $this->roomRepository->load([
            'name' => new Regex('.*' . $query['search'] . '.*', 'i')
        ])->toArray();
        $returnRooms = [];
        foreach ($rooms as $room) {
            /** @var $room Room */
            if ($this->roomSubscriptionRepository->subscriptionExists($this->authorization->getAccount(), $room)) {
                continue; // Only rooms we are NOT subscribed to
            }
            $returnRooms[] = [
                'id' => (string)$room->getId(),
                'name' => $room->getName(),
                'can_subscribe' => true
            ];
        }

        return (new SuccessfulApiResponse())->getResponse($returnRooms);
    }

}
