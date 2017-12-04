<?php

namespace Lisd\Repositories\Room;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\Account\Account;

class Room extends AbstractDocument
{

    public function setName($text)
    {
        $this['name'] = $text;
    }

    public function getName()
    {
        return $this['name'];
    }

    public function setOwner(Account $account)
    {
        $this['owner_id'] = $account->getId();
    }

    public function getOwnerId()
    {
        return $this['owner_id'];
    }

}
