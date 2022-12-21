<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\BotModeEnum;
use App\Enums\MarketTypeEnum;
use App\Enums\GridModeEnum;
use App\Enums\ExchangesEnum;

class Bot extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'lm' => BotModeEnum::class,
        'sm' => BotModeEnum::class,
        'market_type' => MarketTypeEnum::class,
        'grid_mode' => GridModeEnum::class,
        'exchange' => ExchangesEnum::class,
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function start()
    {
        $grid_configs = config('antbot.grid_configs');
        // dd($grid_configs);
        $exchange = Exchange::find($this->exchange_id);
        $args = [
            $exchange->name,
            $this->symbol,
            \Arr::get($grid_configs, $this->grid_mode->value),
            '-lm', $this->lm->value,
            '-lw', $this->lwe,
            '-sm', $this->sm->value,
            '-sw', $this->swe,
        ];

        if ($this->assigned_balance > 0) {
            array_push($args, ['-ab', $this->assigned_balance]);
        }

        if ($this->market_type != MarketTypeEnum::FUTURES) {
            array_push($args, ['-m', $this->market_type->value]);
        }

        $log_file = "/home/antbot/klogs/{$exchange->id}/{$this->symbol}.log";
        $pid = \Python::run('passivbot.py', $args, $log_file);
        \Log::info('Symbol: ' . $this->symbol . ' PID: ' . $pid);

        if ($pid > 0) {
            $this->started_at = now();
            $this->pid = $process->getPid();
            $this->save();
        }
    }

    public function stop()
    {
        $success = \Python::kill($this->pid);
        if ($success) {
            $bot->started_at = NULL;
            $bot->pid = 0;
            $this->save();
        }
    }
}
