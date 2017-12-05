<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Message\Message;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\Room;
use Lisd\Repositories\Room\RoomRepository;
use Magium\ConfigurationManager\Pusher\PusherFactory;

class MessageNotifications implements ProcessorInterface
{

    private $target;
    private $pusherFactory;
    private $roomRepository;
    private $accountRepository;
    private $messageRepository;

    public function __construct(
        PusherFactory $factory,
        MessageRepository $messageRepository,
        AccountRepository $accountRepository,
        RoomRepository $roomRepository
    )
    {
        $this->pusherFactory = $factory;
        $this->roomRepository = $roomRepository;
        $this->messageRepository = $messageRepository;
        $this->accountRepository = $accountRepository;
    }

    public function setTarget(Message $target): MessageNotifications
    {
        $this->target = $target;
        return $this;
    }


    public function execute($id)
    {
        $message = $this->messageRepository->loadById($id);
        if ($message instanceof Message) {
            $room = $this->roomRepository->loadById($message->getRoomId());
            $pusher = $this->pusherFactory->factory();
            $event = (string)$room->getId();
            $account = $this->accountRepository->loadById($message->getAccountId());
            /** @var $account Account */
            /** @var $room Room */
            $payload = [
                'text' => $message->getText(),
                'account' => [
                    'id' => (string)$account->getId(),
                    'given_name' => $account->getGivenName(),
                    'family_name' => $account->getFamilyName(),
                    'picture' => $account->getPicture(),
                ],
                'room' => [
                    'id' => (string)$room->getId(),
                    'name' => $room->getName()
                ]
            ];
            $pusher->trigger('messages', $event, $payload);
        }
    }

    public function getTargetRepository(): AbstractRepository
    {
        return $this->messageRepository;
    }

    public function getTarget(): AbstractDocument
    {
        if (!$this->target) {
            throw new \Exception('Invalid target');
        }
        return $this->target;
    }

}
