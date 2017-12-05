<?php

namespace Lisd\Controller\Controllers\Api;

use Lisd\Controller\AbstractController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\RequestToJson;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\View\Responses\FailedApiResponse;
use Lisd\View\Responses\SuccessfulApiResponse;
use Psr\Http\Message\ResponseInterface;
use Zend\Http\Request;

class UpdateProfileAttribute extends AbstractController
{

    private $authorization;
    private $accountRepository;
    private $request;
    private $disallowed = [
        '_id', 'created_at', 'modified_at', 'object_status', 'sub'
    ];

    public function __construct(
        AuthorizationInterface $authorization,
        AccountRepository $accountRepository,
        RequestToJson $requestToJson
    )
    {
        $this->authorization = $authorization;
        $this->accountRepository = $accountRepository;
        $this->request = $requestToJson;
    }

    public function execute(): ResponseInterface
    {
        $account = $this->authorization->getAccount();
        $json = $this->request->json();
        if (!isset($json['attribute']) || !isset($json['value']) || in_array($json['attribute'], $this->disallowed)) {
            return (new FailedApiResponse())->getResponse('Invalid request');
        }

        $account[$json['attribute']] = $json['value'];
        $this->accountRepository->save($account);

        return (new SuccessfulApiResponse())->getResponse();
    }

}
