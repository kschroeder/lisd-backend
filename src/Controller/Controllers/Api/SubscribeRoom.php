<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\RequestToJson;
use Lisd\Processing\Manager\ProcessManager;
use Lisd\Processing\Processor\SubscriptionNotification;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\Repositories\RoomSubscription\RoomSubscription;
use Lisd\Repositories\RoomSubscription\RoomSubscriptionRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class SubscribeRoom extends AbstractController
{

    private $roomRepository;
    private $request;
    private $authorization;
    private $roomSubscriptionRepository;
    private $processManager;
    private $subscriptionNotification;

    public function __construct(
        AuthorizationInterface $authorization,
        RequestToJson $requestToJson,
        RoomRepository $roomRepository,
        RoomSubscriptionRepository $roomSubscriptionRepository,
        ProcessManager $processManager,
        SubscriptionNotification $subscriptionNotification
    )
    {
        $this->request = $requestToJson;
        $this->authorization = $authorization;
        $this->roomRepository = $roomRepository;
        $this->roomSubscriptionRepository = $roomSubscriptionRepository;
        $this->processManager = $processManager;
        $this->subscriptionNotification = $subscriptionNotification;
    }

    public function execute(): ResponseInterface
    {
        $query = $this->request->json();
        if (!isset($query['room'])) {
            return (new FailedApiResponse())->getResponse('Missing room ID');
        }
        $room = $this->roomRepository->loadById($query['room']);
        if (!$room instanceof Room) {
            return (new FailedApiResponse())->getResponse('Room does not exist');
        }
        if ($this->roomSubscriptionRepository->subscriptionExists($this->authorization->getAccount(), $room)) {
            return (new FailedApiResponse())->getResponse([
                'room' => [
                    'exists' => 'Subscription already exists'
                ]
            ]);
        }

        $subscription = new RoomSubscription();
        $subscription->setAccount($this->authorization->getAccount());
        $subscription->setRoom($room);
        $objectId = $this->roomSubscriptionRepository->save($subscription)->getInsertedId();

        // Send a notification if it's not you
        if ($room->getOwnerId() != $subscription->getAccountId()) {
            $subscription = $this->roomSubscriptionRepository->loadById($objectId);
            /** @var $subscription RoomSubscription */
            $this->subscriptionNotification->setTarget($subscription);
            $this->processManager->queue($this->subscriptionNotification);
        }

        return (new SuccessfulApiResponse())->getResponse([
            'id' => (string)$room->getId(),
            'name' => $room->getName()
        ]);
    }

}
