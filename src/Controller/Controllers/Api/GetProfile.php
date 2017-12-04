<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;

class GetProfile extends AbstractController
{

    private $authorization;

    public function __construct(
        AuthorizationInterface $authorization
    )
    {
        $this->authorization = $authorization;
    }

    public function execute(): ResponseInterface
    {
        $result = [

        ];
        return (new SuccessfulApiResponse())->getResponse($result);
    }

}
