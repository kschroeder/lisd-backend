<?php

namespace Lisd\Controller\Controllers\Api\Validator;

use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Zend\Validator\AbstractValidator;

class AccountExists extends AbstractValidator
{
    private $accountRepository;
    private $account;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function isValid($value)
    {
        $this->account = $this->accountRepository->loadById($value);
        return  $this->account instanceof Account;
    }

}
