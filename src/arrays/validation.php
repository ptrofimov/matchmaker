<?php
namespace arrays\validation {

    function check_matcher($matcherString, $value, $rules)
    {
        $args = [];
        foreach (explode(' ', $matcherString) as $name) {
            if (substr($name, -1) == ')') {
                list($name, $args) = explode('(', $name);
                $args = explode(',', rtrim($args, ')'));
            }
            if (!isset($rules[$name])) {
                throw new \InvalidArgumentException("Validation rule $name not found");
            } elseif (is_callable($rules[$name])) {
                if (!call_user_func_array($rules[$name], array_merge([$value], $args))) {
                    return false;
                }
            } else if ($rules[$name] !== $value) {
                return false;
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
}
