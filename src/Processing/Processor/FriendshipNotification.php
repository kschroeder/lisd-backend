<?php

namespace Lisd\Processing\Processor;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Friendship\Friendship;
use Lisd\Repositories\Friendship\FriendshipRepository;
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
            $friends = $friendship->getFriendship();
            $initiator = $friendship->getInitiatorId();
            if ($initiator) {
                $sendTo = array_diff($friends, [$initiator]);
                $sendTo = array_shift($sendTo);
                $account = $this->accountRepository->loadById($sendTo);
                if ($account instanceof Account) {

                    $payload = [
                        'text' => 'We are now friends',
                        'account' => [
                            'id' => (string)$account->getId(),
                            'given_name' => $account->getGivenName(),
                            'family_name' => $account->getFamilyName(),
                            'picture' => $account->getPicture(),
                        ],
                    ];
                    $this->pusherFactory->factory()->trigger('notifications', (string)$account->getId(), $payload);
                }
            }
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
