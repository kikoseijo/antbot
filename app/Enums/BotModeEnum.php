<?php

namespace App\Enums;

enum BotModeEnum:string {
    case Normal = 'n';
    case Manual = 'm';
    case GracefulStop = 'gs';
    case Panic = 'p';
    case TakeProfitOnly = 't';
}
