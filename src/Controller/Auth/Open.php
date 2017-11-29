<?php

namespace Lisd\Controller\Auth;

use Lisd\Controller\Auth\QueryAuth\Request;
use Lisd\Repositories\Account\Account;
use Psr\Http\Message\RequestInterface;
use QueryAuth\Credentials\Credentials;
use QueryAuth\Factory;
use QueryAuth\Request\RequestValidator;

class Open implements AuthorizationInterface
{

    public function authorize(RequestInterface $request): bool
    {
        return true;
    }

    public function getAccount(): Account
    {
        return null;
    }

}
