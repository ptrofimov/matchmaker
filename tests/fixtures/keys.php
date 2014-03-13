<?php

return [
    'one key' => [
        'schema' => [
            'key' => 100.0,
        ],
        'array' => [
            'key' => 100.0,
        ],
        'isValid' => true,
    ],
    'invalid value' => [
        'schema' => [
            'key' => 100.0,
        ],
        'array' => [
            'key' => 200.0,
        ],
        'isValid' => false,
    ],
    'too many keys' => [
        'schema' => [
            'key' => 100.0,
        ],
        'array' => [
            'key' => 100.0,
            'otherkey' => 100.0,
        ],
        'isValid' => false,
    ],
    'too few keys' => [
        'schema' => [
            'key' => 100.0,
        ],
        'array' => [
        ],
        'isValid' => false,
    ],
];
