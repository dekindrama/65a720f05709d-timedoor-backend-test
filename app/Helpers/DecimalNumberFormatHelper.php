<?php

namespace App\Helpers;

class DecimalNumberFormatHelper
{
    static function run($number) : float {
        return number_format((float)$number, 2, '.', '');
    }
}
