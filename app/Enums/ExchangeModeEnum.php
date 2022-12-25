<?php

namespace App\Enums;

enum ExchangeModeEnum:string {
    case CONSERVATIVE = '1';
    case MODERATE = '2';
    case KAMIKAZE = '3';
}
