<?php

namespace Lisd\View\Responses;

use Lisd\View\View;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class FailedApiResponse extends View
{

    public function getResponse($message = null, $responseCode = 500)
    {
        if (!is_array($message)) {
            $message = [$message];
        }
        return new JsonResponse([
            'status' => 'failed',
            'message' => $message
        ], $responseCode);
    }

}
