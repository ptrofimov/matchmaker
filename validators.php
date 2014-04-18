<?php

function func()
{
//    arr(func_get_args())
//        ->validateKeys(['*' => ['id', 'title', 'status']])
//        ->orThrowException();
//
//    array_keys_valid_assert();

    $a=1;
    is_int($a) ?: throw new Exception('sdf');

    // todo
}

func();