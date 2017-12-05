<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Context;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Friendship\Friendship;
use Lisd\Repositories\Friendship\FriendshipRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class Friends extends AbstractUnauthenticatedController
{

    private $friendshipRepository;
    private $accountRepository;
    private $context;

    public function __construct(
        FriendshipRepository $friendshipRepository,
        AccountRepository $accountRepository,
        Context $context
    )
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->accountRepository = $accountRepository;
        $this->context = $context;
    }

    public function execute(): ResponseInterface
    {
        $account = $this->accountRepository->loadById($this->context['accountId']);
        if ($account) {
            $friends = $this->friendshipRepository->loadByAccount($account);
            $accountId = $account->getId();
            $accounts = [];
            foreach ($friends as $friend) {
                /** @var $friend Friendship */
                $result = array_diff($friend->getFriendship(), [$accountId]);
                $accounts[] = array_shift($result);
            }
            $results = [];
            $friendAccounts = $this->accountRepository->load([
                '_id' => [
                    '$in' => $accounts
                ]
            ])->toArray();
            foreach ($friendAccounts as $friendAccount) {
                /** @var $friendAccount Account  */
                $results[] = [
                    'id' => $friendAccount->getId(),
                    'given_name' => $friendAccount->getGivenName(),
                    'family_name' => $friendAccount->getFamilyName(),
                    'description' => $friendAccount->getDescription(),
                    'picture' => $friendAccount->getPicture()
                ];
            }
            return (new SuccessfulApiResponse())->getResponse($results);
        }
        return (new FailedApiResponse())->getResponse(['account' => ['notExists' => 'Account does not exist']]);
    }

}
