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
        require_once('arrays/validation/rules.php');
        require_once('arrays/keys/valid.php');

        return \arrays\keys\valid($array, $schema);
    }
}

function key_matcher(array $pattern)
{
    $keys = [];
    foreach ($pattern as $k => $v) {
        $chars = ['?' => [0, 1], '*' => [0, PHP_INT_MAX], '!' => [1, 1]];
        if (isset($chars[$last = substr($k, -1)])) {
            $keys[$k = substr($k, 0, -1)] = $chars[$last];
        } elseif ($last == '}') {
            list($k, $range) = explode('{', $k);
            $range = explode(',', rtrim($range, '}'));
            $keys[$k] = count($range) == 1
                ? [$range, $range]
                : [$range[0] === '' ? 0 : $range[0], $range[1] === '' ? PHP_INT_MAX : $range[1]];
        } else {
            $keys[$k] = $chars[$k[0] == ':' ? '*' : '!'];
        }
        array_push($keys[$k], $v, 0);
    }

    return function ($key = null, $value = null) use (&$keys) {
        if (is_null($key)) foreach ($keys as $count) {
            if ($count[3] < $count[0] || $count[3] > $count[1]) return false;
        } else foreach ($keys as $k => &$count) if (matcher($key, $k)) {
            if (!matches($value, $count[2])) return false;
            $count[3]++;
        }
        return true;
    };
}

function matcher($value, $pattern)
{
    $args = [];
    $rules = arrays\validation\rules();
    if (($p = ltrim($pattern, ':')) != $pattern) foreach (explode(' ', $p) as $name) {
        if (substr($name, -1) == ')') {
            list($name, $args) = explode('(', $name);
            $args = explode(',', rtrim($args, ')'));
        }
        if (!isset($rules[$name])) {
            throw new \InvalidArgumentException("Matcher $name not found");
        } elseif (is_callable($rules[$name])) {
            if (!call_user_func_array($rules[$name], array_merge([$value], $args))) {
                return false;
            }
        } elseif ($rules[$name] !== $value) {
            return false;
        }
    } else {
        return $pattern === '' || $value === $pattern;
    }

    return true;
}

function matches($value, $pattern)
{
    if (is_array($pattern)) {
        if (!is_array($value) && !$value instanceof \Traversable) {
            return false;
        }
        $keyMatcher = key_matcher($pattern);
        foreach ($value as $key => $item) {
            if (!$keyMatcher($key, $item)) return false;
        }
        if (!$keyMatcher()) return false;
    } elseif (!matcher($value, $pattern)) {
        return false;
    }

    return true;
}

require_once('arrays/validation/rules.php');

var_dump(matches(
    [1, 2, 3],
    ['*' => ':any']
));

// matchmaker - ultra-fresh PHP matching functions