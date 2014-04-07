<?php

[
    ':numeric' => function ($value) {
            return is_numeric($value);
        },
];


[
    'key?' => 'required',
    '*' => ['key' => 1],
];

function array_validate(array $array, array $schema, &$errors = null)
{
    $errors = [1, 2, 3];
    return true;
}

if (array_validate([1, 2, 3], ['*' => 'number'], $errors)) {
    echo 'valid';
    var_dump($errors);
}

function method()
{
    array_validate(
        func_get_args(),
        ['number gt(5)', 'string']
    );

    if (array_valid($array, $schema)) {
        //...
    }
}

array_validate();
array_keys_validate();

