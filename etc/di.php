<?php

return [
    'instance' => [
        \MongoDB\Client::class => [
            'parameters' => [
                'uri' => 'mongodb://database:27017'
            ]
        ]
    ]
];
