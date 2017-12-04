<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\Controllers\Api\InputFilter\Message;
use Lisd\Controller\RequestToJson;
use Lisd\Processing\Manager\ProcessManager;
use Lisd\Processing\Processor\MessageNotifications;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class PushMessage extends AbstractController
{

    private $jsonRequest;
    private $messageFilter;
    private $messageNotifications;
    private $processManager;
    private $roomRepository;
    private $messageRepository;
    private $authorization;

    public function __construct(
        RequestToJson $json,
        Message $messageFilter,
        MessageNotifications $messageNotifications,
        ProcessManager $processManager,
        RoomRepository $roomRepository,
        MessageRepository $messageRepository,
        AuthorizationInterface $authorization
    )
    {
        $this->jsonRequest = $json;
        $this->messageFilter = $messageFilter;
        $this->messageNotifications = $messageNotifications;
        $this->processManager = $processManager;
        $this->roomRepository = $roomRepository;
        $this->messageRepository = $messageRepository;
        $this->authorization = $authorization;
    }

    public function execute(): ResponseInterface
    {
        $message = $this->jsonRequest->json();
        $this->messageFilter->setData($message);
        if ($this->messageFilter->isValid()) {
            $message = new \Lisd\Repositories\Message\Message();
            $message->setText($this->messageFilter->getValue('text'));
            $message->setRoom($this->roomRepository->loadById($this->messageFilter->getValue('room')));
            $message->setAccount($this->authorization->getAccount());
            $objectId = $this->messageRepository->save($message)->getInsertedId();
            $message = $this->messageRepository->loadById($objectId);
            $this->processManager->queue(
                $this->messageNotifications->setTarget($message)
            );
            return (new SuccessfulApiResponse())->getResponse([
                'id' => (string)$message->getId(),
                'text' => $message->getText(),
                'room' => (string)$message->getRoomId(),
                'created_at' => $message->getCreatedAt()->toDateTime()->getTimestamp()
            ]);
        }
        return (new FailedApiResponse())->getResponse($this->messageFilter->getMessages());
    }

}
