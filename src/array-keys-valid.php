<?php

function array_keys_valid(array $array, array $schema, &$errors = null)
{
    $counters = array_fill_keys($schema, 0);
    $expectedCounters = array_fill_keys($schema, 1);
    foreach ($array as $key => $value) {
        if (isset($counters[$key])) {
            $counters[$key]++;
        }
    }
    foreach ($counters as $key => $counter) {
        if ($counter != $expectedCounters[$key]) {
            return false;
        }
    }

    return true;
}
