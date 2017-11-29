<?php

namespace Lisd\View\Responses;

use Lisd\View\View;
use Zend\Diactoros\Response\HtmlResponse;

class NotFound extends View
{

    public function getResponse()
    {
        return new HtmlResponse('<h1>Not Found</h1>', 404);
    }

}
