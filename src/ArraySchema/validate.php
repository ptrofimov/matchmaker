<?php
namespace ArraySchema;

function validate(array $array, array $schema)
{
    $schemaKeys = array_keys($schema);
    $arrayKeys = array_keys($array);
    sort($schemaKeys);
    sort($arrayKeys);
    if ($schemaKeys !== $arrayKeys) {
        return false;
    }
    foreach ($array as $key => $value) {
        if ($schema[$key] !== $value) {
            return false;
        }
    }

    return true;
}
