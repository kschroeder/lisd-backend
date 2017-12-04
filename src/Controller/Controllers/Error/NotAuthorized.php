<?php

namespace Lisd\Controller\Controllers\Error;

use Lisd\Controller\AbstractUnauthenticatedController;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\TextResponse;

class NotAuthorized extends AbstractUnauthenticatedController
{
    public function execute(): ResponseInterface
    {
        $response = new TextResponse('Not Authorized');
        $response = $response->withStatus(403, 'Not Authorized');

        return $response;
    }


}
