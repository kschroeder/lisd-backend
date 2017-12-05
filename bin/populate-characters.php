<?php

$di = include __DIR__ . '/../etc/bootstrap.php';
/** @var $di \Zend\Di\Di */

$instance = $di->get(\Lisd\Processing\Inline\Characters::class);
$instance->execute();
