<?php

function array_keys_valid(array $array, array $schema, &$errors = null)
{
    $expectedCounters = [];
    foreach ($schema as $key) {
        if (substr($key, -1) == '?') {
            $expectedCounters[substr($key, 0, -1)] = [0, 1];
        } elseif (substr($key, -1) == '*') {
            $expectedCounters[substr($key, 0, -1)] = [0, PHP_INT_MAX];
        } elseif (substr($key, 0, 1) == ':') {
            $expectedCounters[$key] = [0, PHP_INT_MAX];
        } else {
            $expectedCounters[$key] = [1, 1];
        }
    }
    $counters = array_fill_keys(array_keys($expectedCounters), 0);
    foreach ($array as $key => $value) {
        if (isset($counters[$key])) {
            $counters[$key]++;
        } else foreach ($counters as $keyPattern => $counter) {
            if (substr($keyPattern, 0, 1) == ':') {
                $pattern = substr($keyPattern, 1);
                if (is_callable($pattern) && $pattern($key)) {
                    $counters[$keyPattern]++;
                    break;
                }
            } elseif ($keyPattern == '') {
                $counters[$keyPattern]++;
                break;
            }
        }
    }
    $errors = [$expectedCounters, $counters];
    foreach ($counters as $key => $counter) {
        if ($counter < $expectedCounters[$key][0] || $counter > $expectedCounters[$key][1]) {
            return false;
        }
    }

    return true;
}
