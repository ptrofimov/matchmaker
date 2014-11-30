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
    if (($p = ltrim($pattern, ':')) != $pattern) {
        foreach (explode(' ', $p) as $name) {
            $args = [$value];
            if (preg_match('/^(\w+)\((.+)\)$/', $name, $matches)) {
                $name = $matches[1];
                $args = [$value, $matches[2]];
            }
            if (is_callable(rules($name))) {
                if (!call_user_func_array(rules($name), $args)) {
                    return false;
                }
            } elseif (rules($name) !== $value) {
                return false;
            }
        }
        return true;
    } else {
        return $pattern === '' || $value === $pattern;
    }
}
