<?php

namespace App\Enums;

enum GridModeEnum:string {
    case Recursive = 'recursive';
    case StaticGrid = 'static';
    case Neat = 'neat';
    case Custom = 'custom';
}
