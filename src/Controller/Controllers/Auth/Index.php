<?php

namespace Lisd\Controller\Controllers\Auth;

use Lisd\Controller\AbstractUnauthenticatedController;
use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Repositories\Account\Account;
use Lisd\Repositories\Account\AccountRepository;
use Lisd\Repositories\Room\RoomRepository;
use Lisd\View\View;
use Magium\Auth0Factory\Auth0Factory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\RedirectResponse;

class Index extends AbstractUnauthenticatedController
{

    private $auth0;
    private $request;
    private $repository;

    public function __construct(
        Auth0Factory $factory,
        RequestInterface $request,
        AccountRepository $repository,
        RoomRepository $roomRepository
    )
    {
        $this->auth0 = $factory->factory();
        $this->request = $request;
        $this->repository = $repository;
    }

    public function execute(): ResponseInterface
    {

        $result = $this->auth0->getUser();

        if (isset($result['sub'])) {
            $account = $this->repository->loadBySub($result['sub']);
            if (!$account) {
                $account = new Account($result);
                $result = $this->repository->save($account)->getInsertedId();
                $account = $this->repository->loadById($result);
            }
            if (!session_id()) {
                session_start();
            }

            $_SESSION['account'] = $account;

            $redirect = new RedirectResponse('/app');
            return $redirect;
        }
        $this->auth0->login();
    }

}
