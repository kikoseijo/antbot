<?php

namespace App\Enums;

enum GridModeEnum:string {
    case RECURSIVE = 'recursive';
    case STATICGRID = 'static';
    case NEAT = 'neat';
    case CUSTOM = 'custom';
}
