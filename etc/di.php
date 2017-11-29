<?php

return [
    'instance' => [
        \MongoDB\Client::class => [
            'parameters' => [
                'uri' => $_SERVER['MONGO_URI']
            ]
        ]
    ]
];
