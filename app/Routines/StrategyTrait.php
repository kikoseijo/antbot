<?php

namespace App\Routines;

use App\Models\Bot;
use App\Models\Config;
use App\Enums\GridModeEnum;
/**
 * ExecuteBotRoutine
 */
trait StrategyTrait
{
    public function applyNewGrid($params, $reboot = true)
    {
        foreach ($this->bots as $bot) {
            $bot->grid_model = GridModeEnum::CUSTOM;
            $bot->grid_id = $grid_id;
            $bot->save();
            if ($reboot) {
                $bot->restart();
            }
        }
    }

    public function updateWe($new_long_we = null, $new_short_we = null, $reboot = true)
    {
        foreach ($this->bots as $bot) {
            if (!is_null($new_long_we)) {
                $bot->lwe = $new_long_we;
            }
            if (!is_null($new_short_we)) {
                $bot->swe = $new_short_we;
            }
            $bot->save();
            if ($reboot) {
                $bot->restart();
            }
        }
    }

    public function updateMode($new_long_mode = null, $new_short_mode = null, $reboot = true)
    {
        foreach ($this->bots as $bot) {
            if (!is_null($new_long_mode)) {
                $bot->lwe = $new_long_mode;
            }
            if (!is_null($new_short_mode)) {
                $bot->swe = $new_short_mode;
            }
            $bot->save();
            if ($reboot) {
                $bot->restart();
            }
        }
    }

}
