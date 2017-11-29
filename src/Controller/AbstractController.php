<?php

namespace Lisd\Controller;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{

    abstract public function execute(): ResponseInterface;

}
