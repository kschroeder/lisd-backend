<?php

namespace Lisd\Repositories\RoomSubscription;

use Lisd\Repositories\AbstractRepository;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Room\Room;
use MongoDB\Model\BSONArray;

class RoomSubscriptionRepository extends AbstractRepository
{
    public function getCollectionName()
    {
        return 'room_subscriptions';
    }

    public function subscriptionExists(Account $account, Room $room)
    {
        $result = $this->load([
            'account_id' => $account->getId(),
            'room_id' => $room->getId()
        ])->toArray();
        return count($result) > 0;
    }

    /**
     * @param Room $room
     * @return RoomSubscription[]
     */

    public function loadByRoom(Room $room)
    {
        $result = $this->load([
            'room_id' => $room->getId()
        ])->toArray();
        return $result;
    }

    /**
     * @param Account $account
     * @return RoomSubscription[]
     */

    public function loadByAccount(Account $account): array
    {
        $result = $this->load([
            'account_id' => $account->getId()
        ])->toArray();
        return $result;
    }

    public function getOptions()
    {
        return [
            'typeMap' => [
                'array' => BSONArray::class,
                'document' => RoomSubscription::class,
                'root' => RoomSubscription::class,
            ]
        ];
    }

}
