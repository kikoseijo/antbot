<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutinesApiController extends Controller
{
    public function functionName(Routine $routine)
    {
        // $routine->run();
        logi("Routine {$routine->name} has been triggerd by webhook. ()")
    }
}
