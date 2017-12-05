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
        $account = $this->authorization->getAccount();
        $result = [
            'id' => (string)$account->getId(),
            'given_name' => $account->getGivenName(),
            'family_name' => $account->getFamilyName(),
            'picture' => $account->getPicture(),
        ];
        return (new SuccessfulApiResponse())->getResponse($result);
    }

}
