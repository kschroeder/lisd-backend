<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\RequestToJson;
use Lisd\Processing\Manager\ProcessManager;
use Lisd\Processing\Processor\FriendshipNotification;
use Lisd\Repositories\Friendship\Friendship;
use Lisd\Repositories\Friendship\FriendshipRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class CreateFriendship extends AbstractController
{

    private $jsonRequest;
    private $friendshipNotifications;
    private $createFriendshipFilter;
    private $friendshipRepository;
    private $processManager;
    private $authorization;

    public function __construct(
        RequestToJson $json,
        FriendshipNotification $friendshipNotification,
        \Lisd\Controller\Controllers\Api\InputFilter\CreateFriendship $createFriendship,
        FriendshipRepository $friendshipRepository,
        AuthorizationInterface $authorization,
        ProcessManager $processManager
    )
    {
        $this->jsonRequest = $json;
        $this->friendshipNotifications = $friendshipNotification;
        $this->createFriendshipFilter = $createFriendship;
        $this->authorization = $authorization;
        $this->friendshipRepository = $friendshipRepository;
        $this->processManager = $processManager;
    }

    public function execute(): ResponseInterface
    {
        $message = $this->jsonRequest->json();
        $this->createFriendshipFilter->init();
        $this->createFriendshipFilter->setData($message);
        if ($this->createFriendshipFilter->isValid()) {

            $friendship = new Friendship();
            $friendship->setFriends($this->authorization->getAccount(), $this->createFriendshipFilter->getAccount());
            $friendship->setInitiator($this->authorization->getAccount());
            $objectId = $this->friendshipRepository->save($friendship)->getInsertedId();
            $friendship = $this->friendshipRepository->loadById($objectId);
            $this->processManager->queue(
                $this->friendshipNotifications->setTarget($friendship)
            );
            return (new SuccessfulApiResponse())->getResponse([
                'id' => (string)$friendship->getId(),
                'created_at' => $friendship->getCreatedAt()->toDateTime()->getTimestamp()
            ]);
        }
        return (new FailedApiResponse())->getResponse($this->createFriendshipFilter->getMessages());
    }

}
