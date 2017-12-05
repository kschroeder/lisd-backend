<?php

namespace Lisd\Controller\Auth;

use Lisd\Repositories\Account\Account;
use Psr\Http\Message\RequestInterface;

class Session implements AuthorizationInterface
{

    private $account;

    public function authorize(RequestInterface $request): bool
    {
        if (!session_id()) {
            session_start();
        }
        $this->account = $_SESSION['account'] ?? null;
        return $this->account instanceof Account;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

}
