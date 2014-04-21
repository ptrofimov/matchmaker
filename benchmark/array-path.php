<?php

function get_recursive(array $array, $path)
{
    $values = [];
    $segments = explode('.', $path);
    $segment = $segments[0];
    $path = implode('.', array_slice($segments, 1));
    if (count($segments) == 1) {
        $values = isset($array[$segment]) ? [$array[$segment]] : [];
    } elseif ($segment !== '') {
        if (isset($array[$segment]) && is_array($array[$segment])) {
            $values = array_merge($values, get_recursive($array[$segment], $path));
        }
    } else foreach ($array as $item) {
        if (is_array($item)) {
            $values = array_merge($values, get_recursive($item, $path));
        }
    }

    return $values;
}

function get_iterative(array $array, $path)
{
    $values = [];
    $refs = [[$array, explode('.', $path)]];
    while (list($array, $segments) = array_shift($refs)) {
        $segment = $segments[0];
        if (count($segments) == 1) {
            if (isset($array[$segment])) {
                $values[] = $array[$segment];
            }
        } elseif ($segment !== '') {
            if (isset($array[$segment]) && is_array($array[$segment])) {
                $refs[] = [$array[$segment], array_slice($segments, 1)];
            }
        } else foreach ($array as $item) {
            if (is_array($item)) {
                $refs[] = [$item, array_slice($segments, 1)];
            }
        }
    }

    return $values;
}

function get_iterative_refs(array $array, $path)
{
    $values = [];
    $refs = [[&$array, explode('.', $path)]];
    while (list($array, $segments) = array_shift($refs)) {
        $segment = $segments[0];
        if (count($segments) == 1) {
            if (isset($array[$segment])) {
                $values[] = $array[$segment];
            }
        } elseif ($segment !== '') {
            if (isset($array[$segment]) && is_array($array[$segment])) {
                $refs[] = [&$array[$segment], array_slice($segments, 1)];
            }
        } else foreach ($array as $item) if (is_array($item)) {
            $refs[] = [&$item, array_slice($segments, 1)];
        }
        unset($array, $segments);
    }

    return $values;
}


function get_iterative_refs_opt(array $array, $path)
{
    $values = [];
    $segments = explode('.', $path);
    $count = count($segments);
    $refs = [[&$array, 0]];
    while (list($array, $i) = array_shift($refs)) {
        $segment =& $segments[$i];
        if ($i == $count - 1) {
            if (isset($array[$segment])) {
                $values[] = $array[$segment];
            }
        } elseif ($segment !== '') {
            if (isset($array[$segment]) && is_array($array[$segment])) {
                $refs[] = [&$array[$segment], $i + 1];
            }
        } else foreach ($array as $item) if (is_array($item)) {
            $refs[] = [&$item, $i + 1];
        }
        unset($array, $segment);
    }

    return $values;
}


function get_iterative_refs_opt2(array $array, $path)
{
    $values = [];
    $refs = [&$array];
    $paths = [explode('.', $path)];
    while ($array = array_shift($refs)) {
        $segments = array_shift($paths);
        $segment = $segments[0];
        if (count($segments) == 1) {
            if (isset($array[$segment])) {
                $values[] = $array[$segment];
            }
        } elseif ($segment !== '') {
            if (isset($array[$segment]) && is_array($array[$segment])) {
                $refs[] = & $array[$segment];
                $paths[] = array_slice($segments, 1);
            }
        } else foreach ($array as $item) if (is_array($item)) {
            $refs[] = & $item;
            $paths[] = array_slice($segments, 1);
        }
        unset($array, $segments);
    }

    return $values;
}

$arrayItem = ['dictionary' => ['id' => 1]];
$array = array_fill_keys(range(0, 10000), $arrayItem);

$t1 = microtime(true);
$m1 = memory_get_usage();

//$result = get_recursive($array, '.dictionary.id');
//$result = get_iterative($array, '.dictionary.id');
$result = get_iterative_refs($array, '.dictionary.id');
//$result = get_iterative_refs_opt($array, '.dictionary.id');
//$result = get_iterative_refs_opt2($array, '.dictionary.id');

$t2 = microtime(true);
$m2 = memory_get_usage();

var_dump([
    'time' => $t2 - $t1,
    'memory' => $m2 - $m1,
    'memory_peak' => memory_get_peak_usage(),
]);

var_dump(array_slice($result, 0, 10));
