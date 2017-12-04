<?php

namespace Lisd\Repositories\Friendship;

use Lisd\Repositories\AbstractDocument;
use Lisd\Repositories\Account\Account;

class Friendship extends AbstractDocument
{

    public function setFriends(Account $friend1, Account $friend2)
    {
        $this['friendship'] = [
            $friend1->getId(),
            $friend2->getId()
        ];
    }

    public function getFriendship(): array
    {
        return $this['friendship'];
    }

}
