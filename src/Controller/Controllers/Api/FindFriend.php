<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\RequestToJson;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Friendship\FriendshipRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use MongoDB\BSON\Regex;
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
                    'family_name' => new Regex('.*' . $query['search'] . '.*', 'i')
                ], [
                    'given_name' => new Regex('.*' . $query['search'] . '.*', 'i')
                ], [
                    'description' => new Regex('.*' . $query['search'] . '.*', 'i')
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
                'description' => $account->getDescription(),
            ];
        }

        return (new SuccessfulApiResponse())->getResponse($returnAccounts);
    }

}
