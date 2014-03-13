<?php

return [
    'valid value' => [
        'schema' => [
            'key1' => 100.0,
            'key2' => 200.0,
        ],
        'array' => [
            'key1' => 100.0,
            'key2' => 200.0,
        ],
        'isValid' => true,
    ],
    'invalid value' => [
        'schema' => [
            'key1' => 100.0,
            'key2' => 200.0,
        ],
        'array' => [
            'key1' => 100.0,
            'key2' => 201.0,
        ],
        'isValid' => false,
    ],
    'other key' => [
        'schema' => [
            'key1' => 100.0,
            'key2' => 200.0,
        ],
        'array' => [
            'key1' => 100.0,
            'key3' => 200.0,
        ],
        'isValid' => false,
    ],
    'too many keys' => [
        'schema' => [
            'key' => 100.0,
        ],
        'array' => [
            'key' => 100.0,
            'otherKey' => 100.0,
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
