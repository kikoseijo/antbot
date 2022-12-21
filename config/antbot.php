<?php

return [

    'roles' => [
        '1' => 'Admin',
        '2' => 'Client',
    ],

    'exchanges' => [
        'bybit' => 'Bybit',
        'binance' => 'Binance',
        'bitget' => 'Bitget',
    ],

    'market_types' => [
        'futures' => 'Futures',
        'spot' => 'Spot',
    ],

    'grid_modes' => [
        'recursive' => 'Recursive grid',
        'neat' => 'Neat grid',
        'static' => 'Static grid',
        'custom' => 'Custom grid',
    ],

    'grid_configs' => [
        'recursive' => 'recursive.json',
        'neat' => 'neat.json',
        'static' => 'static.json',
    ],

    'bot_modes' => [
        'n' => 'Normal',
        'm' => 'Manual',
        'gs' => 'Gracefully stop',
        't' => 'Take profit only',
        'p' => 'Panic',
    ],

];
