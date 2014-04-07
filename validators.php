<?php

namespace php_array\sdf {
    function test()
    {
        return 5;
    }
}

namespace{
    use my\super\ns;

    echo ns\test();
}