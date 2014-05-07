<?php

if (!function_exists('array_keys_valid')) {

    /**
     * Validates array's keys
     *
     * @param array $array
     * @param array $schema
     *
     * @return mixed
     */
    function array_keys_valid(array $array, array $schema)
    {
        require_once('arrays/validation.php');
        require_once('arrays/validation/matchers.php');
        require_once('arrays/keys/valid.php');

        return \arrays\keys\valid($array, $schema);
    }
}
