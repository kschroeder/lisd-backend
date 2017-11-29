<?php


$di = include __DIR__ . '/../etc/bootstrap.php';
include __DIR__ . '/../etc/http.php';

$di->instanceManager()->setTypePreference(
    \Lisd\Controller\Auth\AuthorizationInterface::class,
    [\Lisd\Controller\Auth\Session::class]
);
$di->get(\Lisd\Controller\FrontController::class)->dispatch();
