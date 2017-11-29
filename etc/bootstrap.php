<?php


require_once __DIR__ . '/../vendor/autoload.php';

$di = new \Zend\Di\Di();
$di->configure(new \Zend\Di\Config(include 'di.php'));
$logger = new \Monolog\Logger('lisd');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../var/log/system.log'));
$di->instanceManager()->setTypePreference(\Psr\Log\LoggerInterface::class, [\Monolog\Logger::class]);
$di->instanceManager()->addSharedInstance($logger, \Monolog\Logger::class);
\Lisd\Repositories\AbstractRepository::setDatabaseName('lisd');
$di->instanceManager()->addSharedInstance(
  \Zend\Router\Http\TreeRouteStack::factory(include 'routers.php'),
    \Zend\Router\Http\TreeRouteStack::class
);
$di->instanceManager()->addSharedInstance($di, Zend\Di\Di::class);

$magiumConfigurationFactory = new \Magium\Configuration\MagiumConfigurationFactory();
$di->instanceManager()->addSharedInstance($magiumConfigurationFactory, get_class($magiumConfigurationFactory));
$config = $magiumConfigurationFactory->getConfiguration();
$di->instanceManager()->addSharedInstance($config, get_class($config));
$di->instanceManager()->addSharedInstance($config, \Magium\Configuration\Config\Repository\ConfigInterface::class);

return $di;
