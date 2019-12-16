<?php

namespace App\Helpers;

class PosHelper
{
    public static function getTempKey()
    {
        return 'customer.basket.'.customer()->id;
    }
}
