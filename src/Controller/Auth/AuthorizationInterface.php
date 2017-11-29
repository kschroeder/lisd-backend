<?php

namespace Lisd\Controller\Auth;

use Lisd\Repositories\Account\Account;
use Psr\Http\Message\RequestInterface;

interface AuthorizationInterface
{

    public function authorize(RequestInterface $request) : bool ;

    public function getAccount() : Account;

}
