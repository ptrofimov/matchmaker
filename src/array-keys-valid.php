<?php

function dictionary()
{
    return [
        'int' => 'is_int',
        'hello' => 'hello',
        'gt' => function ($value, $n) {
                return $value > $n;
            },
        'in' => function ($value) {
                $args = func_get_args();
                array_shift($args);
                return in_array($value, $args);
            },
    ];
}

function check_matcher($matcherString, $value, $dictionary = null)
{
    $args = [];
    $matchers = $dictionary ? $dictionary : dictionary();
    foreach (explode(' ', $matcherString) as $matcher) {
        if (substr($matcher, -1) == ')') { // matcher with arguments
            list($matcher, $args) = explode('(', $matcher);
            $args = explode(',', rtrim($args, ')'));
        }
        if (!isset($matchers[$matcher])) {
            throw new InvalidArgumentException("Matcher $matcher not found");
        }
        if (is_callable($matchers[$matcher])) {
            if (!call_user_func_array($matchers[$matcher], array_merge([$value], $args))) {
                return false;
            }
        } else {
            if ($matchers[$matcher] !== $value) {
                return false;
            }
        }
    }

    return true;
}

function get_expected_counters(array $schema)
{
    $expectedCounters = [];
    foreach ($schema as $key => $item) {
        $nested = null;
        if (is_array($item)) {
            $nested = $item;
            $item = $key;
        }
        if (substr($item, -1) == '?') {
            $expectedCounters[substr($item, 0, -1)] = [0, 1, $nested];
        } elseif (substr($item, -1) == '!') {
            $expectedCounters[substr($item, 0, -1)] = [1, 1, $nested];
        } elseif (substr($item, -1) == '}') {
            list($item, $quantifier) = explode('{', $item);
            $range = explode(',', rtrim($quantifier, '}'));
            if (count($range) == 1) {
                $expectedCounters[$item] = [intval($range), intval($range), $nested];
            } else {
                list($min, $max) = $range;
                $expectedCounters[$item] = [
                    $min === '' ? 0 : intval($min),
                    $max === '' ? PHP_INT_MAX : intval($max),
                    $nested
                ];
            }
        } elseif (substr($item, -1) == '*') {
            $expectedCounters[substr($item, 0, -1)] = [0, PHP_INT_MAX, $nested];
        } elseif (substr($item, 0, 1) == ':') {
            $expectedCounters[$item] = [0, PHP_INT_MAX, $nested];
        } else {
            $expectedCounters[$item] = [1, 1, $nested];
        }
    }

    return $expectedCounters;
}

function increment_counter(array $expectedCounters, array &$counters, $keyPattern, $value)
{
    if ($expectedCounters[$keyPattern][2]) {
        if (is_array($value)
            && array_keys_valid($value, $expectedCounters[$keyPattern][2])
        ) {
            $counters[$keyPattern]++;
        }
    } else {
        $counters[$keyPattern]++;
    }
}

function array_keys_valid(array $array, array $schema, &$errors = null)
{
    $dictionary = dictionary();
    if (isset($schema[':'])) {
        $dictionary = array_merge($dictionary, $schema[':']);
        unset($schema[':']);
    }
    $expectedCounters = get_expected_counters($schema);
    $counters = array_fill_keys(array_keys($expectedCounters), 0);
    foreach ($array as $key => $value) {
        if (isset($counters[$key])) {
            $counters[$key]++;
        } else foreach ($counters as $keyPattern => $counter) {
            if (substr($keyPattern, 0, 1) == ':') {
                $pattern = substr($keyPattern, 1);
                if (is_callable($pattern)) {
                    if ($pattern($key)) {
                        increment_counter($expectedCounters, $counters, $keyPattern, $value);
                        break;
                    }
                } elseif (check_matcher($pattern, $key, $dictionary)) {
                    increment_counter($expectedCounters, $counters, $keyPattern, $value);
                    break;
                }
            } elseif ($keyPattern == '') {
                increment_counter($expectedCounters, $counters, $keyPattern, $value);
                break;
            }
        }
    }
    $result = true;
    $errors = [];
    foreach ($counters as $key => $counter) {
        if ($counter < $expectedCounters[$key][0] || $counter > $expectedCounters[$key][1]) {
            $result = false;
            $errors[$key][] = 'Key is required';
        }
    }

    return $result;
}
