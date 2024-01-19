<?php

namespace App\Routines;

use App\Models\Bot;
use App\Models\Config;

/**
 * TrendTrait
 */
trait TrendTrait
{
    protected function followTrend($timeframe)
    {
        foreach ($this->bots as $bot) {
            $direction = $this->getDirection($bot->symbol->nice_name, $timeframe);
            $this->updateDirectionIfNeeded($bot, $direction);
        }
    }

    protected function updateDirectionIfNeeded(Bot $bot, $new_trend)
    {
        $bot_trend = $bot->trend_15m == 'l' ? 'LONG' : 'SHORT';
        if (in_array($new_trend, ['LONG', 'SHORT']) && $bot_trend != $new_trend) {
            if ($bot->name == 'APT') {
                logi("Trend change {$bot->name} > {$new_trend}");
            }

            $bot->swapWe($new_trend);
            $bot->restart();
        }
    }

    protected function getDirection($symbol, $timeframe = '15m')
    {
        $python_path = Config::find(1)->python_path;
        $cmd = 'cd bots/directional-scalper';
        $cmd .= " && {$python_path} trend.py {$symbol} {$timeframe} 2>&1";
        exec($cmd, $output); // SHORT LONG
        $res = implode(PHP_EOL, $output);

        return $res;

    }
}
