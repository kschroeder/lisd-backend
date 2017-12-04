<?php

namespace Lisd\Controller\Controllers\Error;

use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Context;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\TextResponse;

class NotFound extends AbstractUnauthenticatedController
{
    private $context;

    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
    }

    public function execute(): ResponseInterface
    {
        $response = new TextResponse('Not Found');
        $response = $response->withStatus(404, 'Not Found');

        return $response;
    }


}
