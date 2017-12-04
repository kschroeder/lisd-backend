<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Message\Message;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\RoomRepository;
use Magium\ConfigurationManager\Pusher\PusherFactory;

class MessageNotifications implements ProcessorInterface
{

    private $target;
    private $pusherFactory;
    private $roomRepository;
    private $messageRepository;

    public function __construct(
        PusherFactory $factory,
        MessageRepository $messageRepository,
        RoomRepository $roomRepository
    )
    {
        $this->pusherFactory = $factory;
        $this->roomRepository = $roomRepository;
        $this->messageRepository = $messageRepository;
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
