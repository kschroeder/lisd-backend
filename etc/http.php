<?php

$request = new \Zend\Http\PhpEnvironment\Request();
$psr7Request = \Zend\Psr7Bridge\Psr7ServerRequest::fromZend($request);
/** @var $di \Zend\Di\Di */
$di->instanceManager()->addSharedInstance(
    $psr7Request,
    \Psr\Http\Message\RequestInterface::class
);
$di->instanceManager()->setTypePreference(
    \Psr\Http\Message\ResponseInterface::class,
    [Zend\Diactoros\Response::class]
);
