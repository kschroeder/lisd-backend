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

    public function loadByAccount(Account $account)
    {
        $result = $this->load([
            'friendship' => $account->getId()
        ])->toArray();
        return array_shift($result);
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
