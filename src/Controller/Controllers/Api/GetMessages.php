<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Context;
use Lisd\Controller\RequestToJson;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class GetMessages extends AbstractController
{

    private $context;
    private $messageRepository;
    private $getMessages;

    public function __construct(
        Context $context,
        MessageRepository $messageRepository,
        \Lisd\Controller\Controllers\Api\InputFilter\GetMessages $getMessages
    )
    {
        $this->context = $context;
        $this->messageRepository = $messageRepository;
        $this->getMessages = $getMessages;
    }

    public function execute(): ResponseInterface
    {
        $this->getMessages->setData($this->context);
        if ($this->getMessages->isValid()) {
            $room = $this->getMessages->getRoom();
            $messages = $this->messageRepository->loadByRoomAndCreatedAt(
                $room,
                $this->getMessages->getValue('since')
            );
            $results = [];
            foreach ($messages as $message) {
                $results[] = [
                    'text' => $message->getText(),
                    'room' => $message->getRoomId(),
                    'created_at' => $message->getCreatedAt()->toDateTime()->getTimestamp()
                ];
            }
            return (new SuccessfulApiResponse())->getResponse($results);
        }
        return (new FailedApiResponse())->getResponse(
            $this->getMessages->getMessages()
        );
    }

}
