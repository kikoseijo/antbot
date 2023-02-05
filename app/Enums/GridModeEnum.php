<?php

namespace App\Enums;

enum GridModeEnum:string {
    case RECURSIVE = 'recursive';
    case STATICGRID = 'static';
    case NEAT = 'neat';
    case CLOCK = 'clock';
    case CUSTOM = 'custom';
}
