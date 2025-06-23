<?php

use ManeOlawale\Laravel\Termii\Facades\Termii;

if (!function_exists('sendTermiiMessage')) {
    function sendTermiiMessage($to, $message)
    {
        // Ensure the number starts with '0' and remove it, then add '234'
        if (substr($to, 0, 1) === '0') {
            $to = '234' . substr($to, 1);
        }

        return Termii::send($to, $message); // Modify based on Termii's API
    }
}


if (!function_exists('remove_commas')) {
    /**
     * Remove commas from a numeric string.
     *
     * @param string $value
     * @return string
     */
    function remove_commas($value)
    {
        return str_replace(',', '', $value);
    }
    
}



