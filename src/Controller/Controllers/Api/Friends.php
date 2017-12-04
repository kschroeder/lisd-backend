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
                $accounts[] = array_diff($friend->getFriendship(), [$accountId]);
            }
            $results = [];
            $friendAccounts = $this->accountRepository->load([
                '_id' => [
                    '$in' => $accounts
                ]
            ]);
            foreach ($friendAccounts as $friendAccount) {
                /** @var $friendAccount Account  */
                $results[] = [
                    'first_name' => $friendAccount->getGivenName(),
                    'last_name' => $friendAccount->getFamilyName(),
                    'picture' => $friendAccount->getPicture()
                ];
            }
            return (new SuccessfulApiResponse())->getResponse([
                'friends' => $results
            ]);
        }
        return (new FailedApiResponse())->getResponse(['account' => ['notExists' => 'Account does not exist']]);
    }

}
