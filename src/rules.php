<?php
namespace matchmaker;

/**
 * 1. If $key is null, it returns array of rules
 * 2. If $key is array, it sets new rules
 * 3. Otherwise, it returns matcher by $key
 *
 * @param null|array|string $key
 *
 * @return array|callable
 *
 * @throws \InvalidArgumentException
 *
 * @see https://github.com/ptrofimov/matchmaker - ultra-fresh PHP matching functions
 * @author Petr Trofimov <petrofimov@yandex.ru>
 */
function rules($key = null)
{
    static $rules;

    if (is_null($rules)) {
        $rules = [

            /*
             * General
             */

            'empty' => 'empty',
            'nonempty' =>
                function ($value) {
                    return !empty($value);
                },
            'required' =>
                function ($value) {
                    return !empty($value);
                },
            'in' =>
                function ($value) {
                    return in_array($value, array_slice(func_get_args(), 1));
                },
            'mixed' =>
                function () {
                    return true;
                },
            'any' =>
                function () {
                    return true;
                },

            /*
             * Types
             */

            'array' => 'is_array',
            'bool' => 'is_bool',
            'boolean' => 'is_bool',
            'callable' => 'is_callable',
            'double' => 'is_double',
            'float' => 'is_float',
            'int' => 'is_int',
            'integer' => 'is_integer',
            'long' => 'is_long',
            'numeric' => 'is_numeric',
            'number' => 'is_numeric',
            'object' => 'is_object',
            'real' => 'is_real',
            'resource' => 'is_resource',
            'scalar' => 'is_scalar',
            'string' => 'is_string',

            /*
             * Numbers
             */

            'gt' =>
                function ($value, $n) {
                    return $value > $n;
                },
            'gte' =>
                function ($value, $n) {
                    return $value >= $n;
                },
            'lt' =>
                function ($value, $n) {
                    return $value < $n;
                },
            'lte' =>
                function ($value, $n) {
                    return $value <= $n;
                },
            'negative' =>
                function ($value) {
                    return $value < 0;
                },
            'positive' =>
                function ($value) {
                    return $value > 0;
                },
            'between' =>
                function ($value, $a, $b) {
                    return $value >= $a && $value <= $b;
                },

            /*
             * Strings
             */

            'alnum' => 'ctype_​alnum',
            'alpha' => 'ctype_​alpha',
            'cntrl' => 'ctype_​cntrl',
            'digit' => 'ctype_​digit',
            'graph' => 'ctype_​graph',
            'lower' => 'ctype_​lower',
            'print' => 'ctype_​print',
            'punct' => 'ctype_​punct',
            'space' => 'ctype_​space',
            'upper' => 'ctype_​upper',
            'xdigit' => 'ctype_​xdigit',
            'regexp' =>
                function ($value, $regexp) {
                    return preg_match($regexp, $value);
                },
            'email' =>
                function ($value) {
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                },
            'url' =>
                function ($value) {
                    return filter_var($value, FILTER_VALIDATE_URL);
                },
            'ip' =>
                function ($value) {
                    return filter_var($value, FILTER_VALIDATE_IP);
                },
            'length' =>
                function ($value, $length) {
                    return mb_strlen($value, 'utf-8') == $length;
                },
            'min' =>
                function ($value, $min) {
                    return mb_strlen($value, 'utf-8') >= $min;
                },
            'max' =>
                function ($value, $max) {
                    return mb_strlen($value, 'utf-8') <= $max;
                },
            'contains' =>
                function ($value, $needle) {
                    return strpos($value, $needle) !== false;
                },
            'starts' =>
                function ($value, $string) {
                    return mb_substr($value, 0, mb_strlen($string, 'utf-8'), 'utf-8') == $string;
                },
            'ends' =>
                function ($value, $string) {
                    return mb_substr($value, -mb_strlen($string, 'utf-8'), 'utf-8') == $string;
                },
            'json' =>
                function ($value) {
                    return @json_decode($value) !== null;
                },
            'date' =>
                function ($value) {
                    return strtotime($value) !== false;
                },

            /*
             * Arrays
             */

            'count' =>
                function ($value, $count) {
                    return is_array($value) && count($value) == $count;
                },
            'keys' =>
                function ($value) {
                    if (!is_array($value)) {
                        return false;
                    }
                    foreach (array_slice(func_get_args(), 1) as $key) {
                        if (!array_key_exists($key, $value)) {
                            return false;
                        }
                    }
                    return true;
                },

            /*
             * Objects
             */

            'instance' =>
                function ($value, $class) {
                    return is_object($value) && $value instanceof $class;
                },
            'property' =>
                function ($value, $property, $expected) {
                    return
                        is_object($value)
                        && (property_exists($value, $property) || property_exists($value, '__get'))
                        && $value->$property == $expected;
                },
            'method' =>
                function ($value, $method, $expected) {
                    return
                        is_object($value)
                        && (method_exists($value, $method) || method_exists($value, '__call'))
                        && $value->$method() == $expected;
                },
        ];
    }

    if (is_array($key)) {
        $rules = array_merge($rules, $key);
    } elseif (!is_null($key)) {
        if (!isset($rules[$key])) {
            throw new \InvalidArgumentException("Matcher $key not found");
        }
        return $rules[$key];
    }

    return $rules;
}
