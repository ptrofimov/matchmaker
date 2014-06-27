<?php
namespace matchmaker;

require_once('rules.php');

/**
 * Returns true if $value matches $pattern
 *
 * @param $value
 * @param string $pattern
 *
 * @return bool
 *
 * @see https://github.com/ptrofimov/matchmaker - ultra-fresh PHP matching functions
 * @author Petr Trofimov <petrofimov@yandex.ru>
 */
function matcher($value, $pattern)
{
    $args = [];
    if (($p = ltrim($pattern, ':')) != $pattern) foreach (explode(' ', $p) as $name) {
        if (substr($name, -1) == ')') {
            list($name, $args) = explode('(', $name);
            $args = explode(',', rtrim($args, ')'));
        }
        if (is_callable(rules($name))) {
            if (!call_user_func_array(rules($name), array_merge([$value], $args))) {
                return false;
            }
        } elseif (rules($name) !== $value) {
            return false;
        }
    } else {
        return $pattern === '' || $value === $pattern;
    }

    return true;
}
