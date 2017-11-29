<?php

namespace Lisd\View\Responses;

use Lisd\View\View;
use Zend\Diactoros\Response\HtmlResponse;

class NotAuthorized extends View
{

    public function getResponse()
    {
        return new HtmlResponse('<h1>Not Authorized</h1>', 403);
    }

}
