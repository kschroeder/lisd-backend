<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\Repositories\RoomSubscription\RoomSubscription;
use Lisd\Repositories\RoomSubscription\RoomSubscriptionRepository;
use Magium\ConfigurationManager\Pusher\PusherFactory;

class SubscriptionNotification implements ProcessorInterface
{

    private $target;
    private $pusherFactory;
    private $accountRepository;
    private $roomSubscriptionRepository;
    private $roomRepository;

    public function __construct(
        PusherFactory $factory,
        RoomSubscriptionRepository $roomSubscriptionRepository,
        RoomRepository $roomRepository,
        AccountRepository $accountRepository
    )
    {
        $this->pusherFactory = $factory;
        $this->accountRepository = $accountRepository;
        $this->roomSubscriptionRepository = $roomSubscriptionRepository;
        $this->roomRepository = $roomRepository;
    }

    public function setTarget(RoomSubscription $target): SubscriptionNotification
    {
        $this->target = $target;
        return $this;
    }


    public function execute($id)
    {
        $subscription = $this->roomSubscriptionRepository->loadById($id);
        if ($subscription instanceof RoomSubscription) {
            $subscriberAccount = $this->accountRepository->loadById($subscription->getAccountId());
            /** @var $subscriberAccount Account */
            $room = $this->roomRepository->loadById($subscription->getRoomId());
            /** @var $room Room */
            $ownerAccount = $this->accountRepository->loadById($room->getOwnerId());
            /** @var $ownerAccount Account */
            $message = sprintf('I have just subscribed to your room: %s',
                $room->getName()
            );
            $payload = [
                'text' => $message,
                'account' => [
                    'id' => (string)$subscriberAccount->getId(),
                    'given_name' => $subscriberAccount->getGivenName(),
                    'family_name' => $subscriberAccount->getFamilyName(),
                    'picture' => $subscriberAccount->getPicture(),
                ],
            ];
            $this->pusherFactory->factory()->trigger('notifications', (string)$ownerAccount->getId(), $payload);
        }
    }

    public function getTargetRepository(): AbstractRepository
    {
        return $this->roomSubscriptionRepository;
    }

    public function getTarget(): AbstractDocument
    {
        if (!$this->target) {
            throw new \Exception('Invalid target');
        }
        return $this->target;
    }

}
