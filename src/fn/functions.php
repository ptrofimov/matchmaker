<?php
namespace fn {
    function remove()
    {

    }

    function append()
    {

    }

    function prepend()
    {

    }

    function reindex()
    {

    }

    function change()
    {

    }
}

namespace {
    $array = [1, 2, 3];

    array_path([1, 2, 3], '.dictionary.id', fn\change(function ($item) {
        return $item + 1;
    }));

    array_path($array, ['id', '.dictionary.id']);

    echo array_path($array, 'post.article.title', 'untitled');

    arr($array, '.dictionary.id', function (&$value, $path, $root) {
        if (arr($root, $path[0] . '.id') == 5) {
            $value = 1;
        }
    });
}