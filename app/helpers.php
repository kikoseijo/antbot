<?php

if (!function_exists('bignumber')) {
    function bignumber($number): string
    {
        $human_readable = new \NumberFormatter(
            'en_US',
            \NumberFormatter::PADDING_POSITION
        );

        return  $human_readable->format($number);
    }
}

if (!function_exists('number')) {
    function number($number, $decimals = 2): string
    {
        // $abs_num = abs($number);
        // $decimals = strlen(substr(strrchr($number, "."), 1));
        // if ($abs_num < 0.01) {
        //     $decimals = 3;
        // }
        // if ($abs_num < 0.001) {
        //     $decimals = 4;
        // }
        // if ($abs_num < 0.0001) {
        //     $decimals = 5;
        // }
        // if ($abs_num < 0.00001) {
        //     $decimals = 6;
        // }
        // if ($abs_num < 0.000001) {
        //     $decimals = 7;
        // }
        // if ($abs_num < 0.0000001) {
        //     $decimals = 8;
        // }
        if (app()->getLocale() == 'es') {
            return number_format(floatval($number), $decimals, ',', '.');
        } else {
            return number_format(floatval($number), $decimals, '.', ',');
        }
    }
}

if (!function_exists('pretty_print_array')) {
    function pretty_print_array(array $array_data)
    {
        print("<pre>" . print_r($array_data, true) . "</pre>");
    }
}

if (!function_exists('logi')) {
    function logi($data)
    {
        Log::info(transform_log($data));
    }
}

if (!function_exists('loge')) {
    function loge($data)
    {
        Log::error(transform_log($data));
    }
}

if (!function_exists('logc')) {
    function logc($data)
    {
        Log::critical(transform_log($data));
    }
}

if (!function_exists('transform_log')) {
    function transform_log($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        } else {
            return $data;
        }
    }
}
