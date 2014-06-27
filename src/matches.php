<?php
namespace matchmaker;

/**
 * Returns true if $value matches $pattern
 *
 * @param $value
 * @param $pattern
 *
 * @return bool
 *
 * @see https://github.com/ptrofimov/matchmaker - ultra-fresh PHP matching functions
 * @author Petr Trofimov <petrofimov@yandex.ru>
 */
function matches($value, $pattern)
{
    require_once('key_matcher.php');

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
