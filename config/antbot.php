<?php

return [

    // config('antbot.paths.passivbot_path')
    'paths' => [
        'python' => env('PYTHON_PATH', 'python'),
        'passivbot_path' => env('PASSIVBOT_PATH', '/home/antbot/passivbot'),
        'passivbot_logs' => env('PASSIVBOT_LOGS_PATH', '/home/antbot/logs'),
    ],

    'roles' => [
        '1' => 'Admin',
        '2' => 'User',
    ],

    'exchanges' => [
        'bybit' => 'Bybit',
        'binance' => 'Binance',
        'binance_us' => 'Binance US',
        'bitget' => 'Bitget',
        'okx' => 'OKX',
    ],

    'exchange_mode' => [
        '1' => 'Conservative',
        '2' => 'Moderate',
        '3' => 'Kamikaze',
    ],

    'market_types' => [
        'futures' => 'Futures',
        'spot' => 'Spot',
    ],

    'grid_modes' => [
        'recursive' => 'Recursive mode',
        'neat' => 'Neat mode',
        'static' => 'Static mode',
        'clock' => 'Clock mode',
        'custom' => 'Custom',
    ],

    'grid_configs' => [
        'recursive' => 'configs/live/recursive.json',
        'neat' => 'configs/live/neat.json',
        'static' => 'configs/live/static.json',
        'clock' => 'configs/live/clock.json',
    ],

    'bot_modes' => [
        'n' => 'Normal',
        'm' => 'Manual',
        'gs' => 'Gracefully stop',
        't' => 'Take profit only',
        'p' => 'Panic',
    ],


    'css' => [
        'thead' => 'text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400',
        'tbody' => 'bg-white dark:bg-gray-900'
    ],

    'symbols' => [
        'statuses' => [ // No se usa.
            '1' => 'Trading',
            '2' => 'Settling',
            '3' => 'Closed'
        ]
    ]

];
