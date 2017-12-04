<?php

namespace Lisd\Controller;

use Lisd\Controller\Auth\AuthorizationInterface;
use Lisd\Controller\Controllers\Error\InternalServerError;
use Lisd\Controller\Controllers\Error\NotAuthorized;
use Lisd\Controller\Controllers\Error\NotFound;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Di\Di;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Http\Request;
use Zend\Router\Http\TreeRouteStack;
use Zend\Router\RouteMatch;

class FrontController
{
    private $di;
    private $authorization;
    private $request;
    private $router;
    private $context;

    public function __construct(
        Di $di,
        AuthorizationInterface $authorization,
        RequestInterface $request,
        TreeRouteStack $router,
        Context $context
    )
    {
        $this->di = $di;
        $this->authorization = $authorization;
        $this->request = $request;
        $this->router = $router;
        $this->context = $context;
    }

    public function setAuthorization(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    public function dispatch()
    {
        ob_start();
        $controller = $action = 'error';
        $path = $this->request->getUri()->getPath();
        $request = new Request();
        $request->setUri('http://localhost' . $path); // Cheating,yes.
        $match = $this->router->match($request);
        if ($match instanceof RouteMatch) {
            $controller = $match->getParam('controller');
            $action = $match->getParam('action');
            foreach ($match->getParams() as $key => $value) {
                $context[$key] = $value;
            }
        }

        $controllerClass = $this->formatControllerClass($controller, $action);
        if (!class_exists($controllerClass)) {
            $controllerClass = NotFound::class;
        }

        $response = $this->execute($controllerClass);

        ob_clean();
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

    public function formatControllerClass($controller, $action): string
    {
        $result = 'Lisd\Controller\Controllers\\' . $this->formatCase($controller) . '\\' . $this->formatCase($action);
        return $result;
    }

    public function formatCase($part): string
    {
        $part = strtolower($part);
        $part = explode('_', $part);
        foreach ($part as &$item) {
            $item = ucfirst($item);
        }
        $part = implode('', $part);
        return $part;
    }

    public function execute($controllerClass): ResponseInterface
    {
        try {
            $controllerInstance = $this->di->get($controllerClass);
            if ($controllerInstance instanceof AbstractController) {
                $ignoreAuthentication = $controllerInstance instanceof AbstractUnauthenticatedController;
                if (!$ignoreAuthentication) {
                    if (!$this->authorization->authorize($this->request)) {
                        $controllerInstance = $this->di->get(NotAuthorized::class);
                    }
                }
                $response = $controllerInstance->execute();
            } else {
                throw new \Exception('Invalid controller');
            }

        } catch (\Exception $e) {;
            $this->context['message'] = $e->getMessage();
            $this->context['originalController'] = $controllerInstance;
            $controllerInstance = $this->di->get(InternalServerError::class);
            /** @var $controllerInstance InternalServerError  */
            $response = $controllerInstance->execute();
        }

        return $response;
    }

}
