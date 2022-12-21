<?php

namespace App\Enums;

enum MarketTypeEnum:string {
    case SPOT = 'spot';
    case FUTURES = 'futures';
}
