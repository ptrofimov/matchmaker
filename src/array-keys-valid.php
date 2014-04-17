<?php

function dictionary()
{
    return [
        'int' => 'is_int',
        'hello' => 'hello',
    ];
}

function check_matcher($matcher, $value, $dictionary = null)
{
    $matchers = $dictionary ? $dictionary : dictionary();
    if (!isset($matchers[$matcher])) {
        throw new InvalidArgumentException("Matcher $matcher not found");
    }
    if (is_callable($matchers[$matcher])) {
        return (bool) $matchers[$matcher]($value);
    }

    return $matchers[$matcher] === $value;
}

function array_keys_valid(array $array, array $schema, &$errors = null)
{
    $dictionary = dictionary();
    $expectedCounters = [];
    foreach ($schema as $index => $key) {
        if ($index === ':') {
            $dictionary = array_merge($dictionary, $key);
            continue;
        }
        $nested = null;
        if (is_array($key)) {
            $nested = $key;
            $key = $index;
        }
        if (substr($key, -1) == '?') {
            $expectedCounters[substr($key, 0, -1)] = [0, 1, $nested];
        } elseif (substr($key, -1) == '!') {
            $expectedCounters[substr($key, 0, -1)] = [1, 1, $nested];
        } elseif (substr($key, -1) == '}') {
            list($key, $quantifier) = explode('{', $key);
            $quantifier = rtrim($quantifier, '}');
            $range = explode(',', $quantifier);
            if (count($range) == 1) {
                $expectedCounters[$key] = [intval($range), intval($range), $nested];
            } else {
                list($min, $max) = $range;
                $expectedCounters[$key] = [
                    $min === '' ? 0 : intval($min),
                    $max === '' ? PHP_INT_MAX : intval($max),
                    $nested
                ];
            }
        } elseif (substr($key, -1) == '*') {
            $expectedCounters[substr($key, 0, -1)] = [0, PHP_INT_MAX, $nested];
        } elseif (substr($key, 0, 1) == ':') {
            $expectedCounters[$key] = [0, PHP_INT_MAX, $nested];
        } else {
            $expectedCounters[$key] = [1, 1, $nested];
        }
    }
    $counters = array_fill_keys(array_keys($expectedCounters), 0);
    foreach ($array as $key => $value) {
        if (isset($counters[$key])) {
            $counters[$key]++;
        } else foreach ($counters as $keyPattern => $counter) {
            if (substr($keyPattern, 0, 1) == ':') {
                $pattern = substr($keyPattern, 1);
                if (is_callable($pattern)) {
                    if ($pattern($key)) {
                        if ($expectedCounters[$keyPattern][2]) {
                            if(is_array($value)
                            && array_keys_valid($value, $expectedCounters[$keyPattern][2])){
                                $counters[$keyPattern]++;
                            }
                        }else{
                            $counters[$keyPattern]++;
                        }
                        break;
                    }
                } elseif (check_matcher($pattern, $key, $dictionary)) {
                    if ($expectedCounters[$keyPattern][2]) {
                        if(is_array($value)
                            && array_keys_valid($value, $expectedCounters[$keyPattern][2])){
                            $counters[$keyPattern]++;
                        }
                    }else{
                        $counters[$keyPattern]++;
                    }
                    break;
                }
            } elseif ($keyPattern == '') {
                if ($expectedCounters[$keyPattern][2]) {
                    if(is_array($value)
                        && array_keys_valid($value, $expectedCounters[$keyPattern][2])){
                        $counters[$keyPattern]++;
                    }
                }else{
                    $counters[$keyPattern]++;
                }
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
