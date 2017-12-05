<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\Context;
use Lisd\Controller\Controllers\Api\InputFilter\Message;
use Lisd\Controller\RequestToJson;
use Lisd\Processing\Manager\ProcessManager;
use Lisd\Processing\Processor\FriendshipNotification;
use Lisd\Processing\Processor\MessageNotifications;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Friendship\Friendship;
use Lisd\Repositories\Friendship\FriendshipRepository;
use Lisd\Repositories\Message\MessageRepository;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class FindFriend extends AbstractController
{

    private $friendshipRepository;
    private $accountRepository;
    private $request;
    private $authorization;

    public function __construct(
        FriendshipRepository $friendshipRepository,
        AccountRepository $accountRepository,
        AuthorizationInterface $authorization,
        RequestToJson $requestToJson
    )
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->accountRepository = $accountRepository;
        $this->request = $requestToJson;
        $this->authorization = $authorization;
    }

    public function execute(): ResponseInterface
    {
        $query = $this->request->json();
        if (!isset($query)) {
            return (new FailedApiResponse())->getResponse('Missing search text');
        }
        $accounts = $this->accountRepository->load([
            '$or' => [
                [
                    'family_name' => $query['search']
                ], [
                    'given_name' => $query['search']
                ]
            ],
            '_id' => [
                '$ne' => $this->authorization->getAccount()->getId()
            ]
        ]);
        $returnAccounts = [];
        foreach ($accounts as $account) {
            /** @var $account Account */
            $returnAccounts[] = [
                'id' => (string)$account->getId(),
                'can_create_friendship' => !$this->friendshipRepository->exists($this->authorization->getAccount(), $account),
                'given_name' => $account->getGivenName(),
                'family_name' => $account->getFamilyName(),
                'picture' => $account->getPicture(),
            ];
        }

        return (new SuccessfulApiResponse())->getResponse($returnAccounts);
    }

}
