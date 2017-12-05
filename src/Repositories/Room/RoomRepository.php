<?php

namespace Lisd\Repositories\Room;

use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use MongoDB\Model\BSONArray;

class RoomRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'room';
    }

    public function loadByName($name)
    {
        $result = $this->load([
            'name' => $name
        ])->toArray();
        return array_shift($result);
    }

    /**
     * @param Account $account
     * @return Room[]
     */

    public function loadByAccount(Account $account): array
    {
        $result = $this->load([
            'owner_id' => $account->getId()
        ])->toArray();
        return $result;
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => Room::class,
                'root' => Room::class,
            ]
        ];
    }

}
