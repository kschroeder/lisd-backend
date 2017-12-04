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
        'friends' => [
            'type' => \Zend\Router\Http\Literal::class,
            'options' => [
                'route' => '/api/friends/:accountId',
                'defaults' => [
                    'controller' => 'Api',
                    'action' => 'friends',
                ],
            ],
        ],
        'api' => [
            'type' => \Zend\Router\Http\Segment::class,
            'options' => [
                'route' => '/api',
                'defaults' => [
                    'controller' => 'Api',
                    'action' => 'index',
                ],
            ],
        ],
        'auth' => [
            'type' => \Zend\Router\Http\Literal::class,
            'options' => [
                'route' => '/auth',
                'defaults' => [
                    'controller' => 'Auth',
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

