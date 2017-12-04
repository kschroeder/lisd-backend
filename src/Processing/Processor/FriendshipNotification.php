<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Friendship\Friendship;
use Lisd\Repositories\Friendship\FriendshipRepository;
use Lisd\Repositories\Message\Message;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\RoomRepository;
use Magium\ConfigurationManager\Pusher\PusherFactory;

class FriendshipNotification implements ProcessorInterface
{

    private $target;
    private $pusherFactory;
    private $accountRepository;
    private $frienshipRepository;

    public function __construct(
        PusherFactory $factory,
        FriendshipRepository $friendshipRepository,
        AccountRepository $accountRepository
    )
    {
        $this->pusherFactory = $factory;
        $this->accountRepository = $accountRepository;
        $this->frienshipRepository = $friendshipRepository;
    }

    public function setTarget(Friendship $target): FriendshipNotification
    {
        $this->target = $target;
        return $this;
    }


    public function execute($id)
    {
        $friendship = $this->frienshipRepository->loadById($id);
        if ($friendship instanceof Friendship) {

        }
    }

    public function getTargetRepository(): AbstractRepository
    {
        return $this->frienshipRepository;
    }

    public function getTarget(): AbstractDocument
    {
        if (!$this->target) {
            throw new \Exception('Invalid target');
        }
        return $this->target;
    }

}
