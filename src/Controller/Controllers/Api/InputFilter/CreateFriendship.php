<?php

namespace Lisd\Controller\Controllers\Api\InputFilter;

use Lisd\Controller\Controllers\Api\Validator\AccountExists;
use Lisd\Controller\Controllers\Api\Validator\RoomExists;
use Lisd\Controller\Controllers\Api\Validator\RoomNotExists;
use Lisd\Repositories\Account\Account;
use Zend\InputFilter\InputFilter;

class CreateFriendship extends InputFilter
{

    private $accountExists;

    public function __construct(
        AccountExists $accountExists
    )
    {
        $this->accountExists = $accountExists;
    }

    public function getAccount(): ?Account
    {
        return $this->accountExists->getAccount();
    }

    public function init()
    {
        $this->add([
            'name' => 'account',
            'required' => true,
            'validators' => [
                $this->accountExists
            ]
        ]);

    }

}
