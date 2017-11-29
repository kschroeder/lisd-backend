<?php

namespace Lisd\View\Responses;

use Lisd\View\View;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class SuccessfulApiResponse extends View
{

    public function getResponse($results = null, $responseCode = 200)
    {
        return new JsonResponse([
            'status' => 'success',
            'result' => $results
        ], $responseCode);
    }

}
