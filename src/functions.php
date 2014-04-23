<?php

if (!function_exists('array_keys_valid')) {
    function array_keys_valid(array $array, array $schema)
    {
        require_once('functions/array_keys_valid.php');

        return functions\array_keys_valid($array, $schema);
    }
}
