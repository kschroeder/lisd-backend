<?php
$di = include __DIR__ . '/../etc/bootstrap.php';
/** @var $di \Zend\Di\Di */
$class = \Lisd\Processing\InboundAdapter\Sqs::class;

$instance = $di->get($class);
if (!$instance instanceof Lisd\Processing\InboundAdapter\Sqs) {
    die($class . " is not an instance of Lisd\Processing\InboundAdapter\Sqs\n");
}

$instance->execute();

