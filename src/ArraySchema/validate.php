<?php
namespace ArraySchema;

function validate(array $array, array $schema)
{
    foreach ($array as $key => $value) {
        if (!array_key_exists($key, $schema)) {
            return false;
        }
        if ($schema[$key] !== $value) {
            return false;
        }
    }

    return true;
}
