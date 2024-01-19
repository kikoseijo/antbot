<?php

namespace App\Routines;

use App\Models\Bot;
use App\Models\Config;
use App\Models\Routine;

/**
 * ExecuteRoutine
 */
class ExecuteRoutine
{
    use TrendTrait;
    use StrategyTrait;

    protected $bots;

    function __construct()
    {
        $this->bots = Bot::with('symbol')->onTrend()->get();
    }

    public function run($routine = 'follow_15m_trend')
    {
        return match($routine){
            'follow_30m_trend' => $this->followTrend('30m'),
            'follow_15m_trend' => $this->followTrend('15m'),
            'follow_5m_trend' => $this->followTrend('5m')
        };
    }

    public function runRoutine(Routine $routine, $sleep_time)
    {
        $params = (object) $routine->action;
        $bots = auth()->user()->bots()->onRoutine()->get();
        foreach ($bots as $bot) {
            foreach (['lwe', 'swe', 'lm', 'sm','grid_mode', 'grid_id'] as $value) {
                if ($params->{$value}) {
                    $bot->{$value} = $params->{$value};
                }
            }
            $bot->save();
            $bot->restart();
            sleep($sleep_time);
        }

    }

}
