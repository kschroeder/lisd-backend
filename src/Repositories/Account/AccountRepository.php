<?php

namespace Lisd\Repositories\Account;

use Lisd\Repositories\AbstractRepository;
use MongoDB\Model\BSONArray;

class AccountRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'accounts';
    }

    public function loadByUserId($userId): ?Account
    {
        $result = $this->load([
            'user_id' => $userId
        ])->toArray();
        return array_shift($result);
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => Account::class,
                'root' => Account::class,
            ]
        ];
    }

}
