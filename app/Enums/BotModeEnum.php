<?php

namespace App\Enums;

enum BotModeEnum:string {
    case NORMAL = 'n';
    case MANUAL = 'm';
    case GRACEFULSTOP = 'gs';
    case PANIC = 'p';
    case TAKEPROFITONLY = 't';
}
