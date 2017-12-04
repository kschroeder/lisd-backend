<?php

namespace Lisd\Repositories\Message;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Room\Room;

class Message extends AbstractDocument
{

    public function setText($text)
    {
        $this['text'] = $text;
    }

    public function getText()
    {
        return $this['text'];
    }

    public function setRoom(Room $room)
    {
        $this['room_id'] = $room->getId();
    }

    public function getRoomId()
    {
        return $this['room_id'];
    }

    public function setAccount(Account $account)
    {
        $this['account_id'] = $account->getId();
    }

    public function getAccountId()
    {
        return $this['account_id'];
    }

}
