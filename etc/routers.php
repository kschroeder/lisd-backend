<?php


return [
    'routes' => [
        'home' => [
            'type' => \Zend\Router\Http\Literal::class,
            'options' => [
                'route' => '/',
                'defaults' => [
                    'controller' => 'Index',
                    'action' => 'index',
                ],
            ],
        ],
        'api' => [
            'type' => \Zend\Router\Http\Literal::class,
            'options' => [
                'route' => '/api',
                'defaults' => [
                    'controller' => 'Api',
                    'action' => 'index',
                ],
            ],
        ],
        'error' => [
            'type' => \Zend\Router\Http\Segment::class,
            'options' => [
                'route' => '/error[/:action]',
                'defaults' => [
                    'controller' => 'Error',
                    'action' => 'Error',
                ],
            ],
        ],
    ]
];

