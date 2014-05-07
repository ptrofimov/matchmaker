<?php
namespace arrays\keys {

    use \arrays\validation as v;

    function valid(array $array, array $schema)
    {
        $dictionary = v\matchers();
        if (isset($schema[':'])) {
            $dictionary = array_merge($dictionary, $schema[':']);
            unset($schema[':']);
        }
        $expectedCounters = v\get_expected_counters($schema);
        $counters = array_fill_keys(array_keys($expectedCounters), 0);
        foreach ($array as $key => $value) {
            if (isset($counters[$key])) {
                $counters[$key]++;
            } else foreach ($counters as $keyPattern => $counter) {
                if (substr($keyPattern, 0, 1) == ':') {
                    $pattern = substr($keyPattern, 1);
                    if (is_callable($pattern)) {
                        if ($pattern($key)) {
                            v\increment_counter($expectedCounters, $counters, $keyPattern, $value);
                            break;
                        }
                    } elseif (v\check_matcher($pattern, $key, $dictionary)) {
                        v\increment_counter($expectedCounters, $counters, $keyPattern, $value);
                        break;
                    }
                } elseif ($keyPattern == '') {
                    v\increment_counter($expectedCounters, $counters, $keyPattern, $value);
                    break;
                }
            }
        }
        foreach ($counters as $key => $counter) {
            if ($counter < $expectedCounters[$key][0] || $counter > $expectedCounters[$key][1]) {
                return false;
            }
        }

        return true;
    }
}
