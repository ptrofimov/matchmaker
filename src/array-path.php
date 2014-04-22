<?php

if (!function_exists('array_path')) {
    function array_path(array $array, $path, $defaultValue = null)
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
}
