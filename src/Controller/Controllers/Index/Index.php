<?php

namespace Lisd\Controller\Controllers\Index;

use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\View\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Index extends AbstractUnauthenticatedController
{

    private $request;

    public function __construct(
        RequestInterface $request,
        AuthorizationInterface $authorization
    )
    {
        $this->request = $request;
    }

    public function execute(): ResponseInterface
    {

        return new RedirectResponse('/auth');
    }

}
