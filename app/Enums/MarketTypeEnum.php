<?php

namespace App\Enums;

enum MarketTypeEnum:string {
    case Spot = 'spot';
    case Futures = 'futures';
}
