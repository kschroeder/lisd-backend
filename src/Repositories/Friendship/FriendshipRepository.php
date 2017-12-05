<?php

namespace Lisd\Repositories\Friendship;

use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use MongoDB\Model\BSONArray;

class FriendshipRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'friendships';
    }

    public function exists(Account $account1, Account $account2)
    {
        $pipeline = [
            [
                '$match' => [
                    'friendship' => $account1->getId()
                ]
            ],
            [
                '$match' => [
                    'friendship' => $account2->getId()
                ]
            ],

        ];

        $result = $this->getCollection()->aggregate($pipeline)->toArray();

        return count($result) > 0;
    }

    public function loadByAccount(Account $account)
    {
        $result = $this->load([
            'friendship' => $account->getId()
        ])->toArray();
        return $result;
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => Friendship::class,
                'root' => Friendship::class,
            ]
        ];
    }

}
