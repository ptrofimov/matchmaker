<?php
namespace ArraySchema;

function validate(array $array, array $schema)
{
    foreach ($schema as $key => $value) {
        if (!array_key_exists($key, $array)) {
            return false;
        }
        if ($array[$key] !== $value) {
            return false;
        }
    }

    return true;
}
