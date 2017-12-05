<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\Repositories\RoomSubscription\RoomSubscriptionRepository;
use Lisd\View\Responses\SuccessfulApiResponse;
use MongoDB\BSON\ObjectID;
use Psr\Http\Message\ResponseInterface;

class GetMessages extends AbstractController
{

    private $messageRepository;
    private $roomRepository;
    private $authorization;
    private $roomSubscriptionRepository;
    private $accountRepository;
    private $accounts = [];

    public function __construct(
        MessageRepository $messageRepository,
        RoomRepository $roomRepository,
        RoomSubscriptionRepository $roomSubscriptionRepository,
        AccountRepository $accountRepository,
        AuthorizationInterface $authorization
    )
    {
        $this->messageRepository = $messageRepository;
        $this->roomRepository = $roomRepository;
        $this->authorization = $authorization;
        $this->accountRepository = $accountRepository;
        $this->roomSubscriptionRepository = $roomSubscriptionRepository;
    }

    protected function getAccount(ObjectID $id)
    {
        $test = (string)$id;
        if (!isset($this->accounts[$test])) {
            $account = $this->accountRepository->loadById($id);
            /** @var $account Account */
            $this->accounts[$test] = [
                'id' => (string)$account->getId(),
                'family_name' => (string)$account->getFamilyName(),
                'given_name' => (string)$account->getGivenName(),
                'picture' => (string)$account->getPicture(),
            ];
        }
        return $this->accounts[$test];
    }

    protected function getRoom(array $rooms, ObjectID $room)
    {
        foreach ($rooms as $roomItem) {
            if ($roomItem instanceof Room) {
                if ($roomItem->getId() == $room) {
                    return [
                        'id' => (string)$roomItem->getId(),
                        'name' => $roomItem->getName()
                    ];
                }
            }
        }
        return null;
    }

    public function execute(): ResponseInterface
    {
        $subscriptions = $this->roomSubscriptionRepository->loadByAccount($this->authorization->getAccount());
        $roomIds = [];
        foreach ($subscriptions as $subscription) {
            $roomIds[] = $subscription->getRoomId();
        }
        $rooms = $this->roomRepository->load([
            '_id' => [
                '$in' => $roomIds
            ]
        ])->toArray();

        $messages = $this->messageRepository->loadByRooms($rooms);
        $results = [];
        foreach ($messages as $message) {
            $results[] = [
                'text' => $message->getText(),
                'room' => $this->getRoom($rooms, $message->getRoomId()),
                'account' => $this->getAccount($message->getAccountId()),
                'created_at' => $message->getCreatedAt()->toDateTime()->getTimestamp()
            ];
        }
        return (new SuccessfulApiResponse())->getResponse($results);

    }

}
