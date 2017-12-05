<?php

namespace Lisd\Repositories\RoomSubscription;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Room\Room;
use MongoDB\BSON\ObjectID;

class RoomSubscription extends AbstractDocument
{

    public function setRoom(Room $room)
    {
        $this['room_id'] = $room->getId();
    }

    public function getRoomId(): ObjectID
    {
        return $this['room_id'];
    }

    public function setAccount(Account $account)
    {
        $this['account_id'] = $account->getId();
    }

    public function getAccountId(): ObjectID
    {
        return $this['account_id'];
    }

}
