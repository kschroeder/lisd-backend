<?php

namespace Lisd\Controller;

use Psr\Http\Message\RequestInterface;

class RequestToJson
{

    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function json()
    {
        $body = $this->request->getBody();
        $body->rewind();
        $data = $body->getContents();
        $data= json_decode($data, true);
        return $data;
    }

}
