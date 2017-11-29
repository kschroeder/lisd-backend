<?php


$di = include __DIR__ . '/../etc/bootstrap.php';
include __DIR__ . '/../etc/http.php';

$di->instanceManager()->setTypePreference(
    \Clairvoyant\Controller\Auth\AuthorizationInterface::class,
    [\Clairvoyant\Controller\Auth\Session::class]
);
$di->get(\Clairvoyant\Controller\FrontController::class)->dispatch();
