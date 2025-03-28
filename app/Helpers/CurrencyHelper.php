<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount)
    {
        return 'R' . number_format($amount, 2);
    }
}
