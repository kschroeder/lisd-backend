<?php

namespace Lisd\Controller\Controllers\Error;

use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Context;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\TextResponse;

class InternalServerError extends AbstractUnauthenticatedController
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

        $message = $this->context['message']?': ' . $this->context['message']:'';
        $response = new TextResponse('Internal Server Error' . $message);
        $response = $response->withStatus(500, 'Internal Server Error');

        return $response;
    }

}
