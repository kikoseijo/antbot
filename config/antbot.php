<?php

return [

    'roles' => [
        '1' => 'Admin',
        '2' => 'Client',
    ],

    'exchanges' => [
        'Bybit' => 'bybit',
        'Binance' => 'binance',
        'Bitget' => 'bitget',
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

    'bot_modes' => [
        'n' => 'Normal',
        'm' => 'Manual',
        'gs' => 'Gracefully stop',
        't' => 'Take profit only',
        'p' => 'Panic',
    ],
];
