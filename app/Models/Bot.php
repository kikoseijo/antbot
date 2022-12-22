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

    public function getIsRunningAttribute()
    {
        return $this->started_at && $this->pid > 0;
    }

    public function getLogPathAttribute()
    {
        $log_path = config('antbot.paths.logs_path');

        return "{$log_path}/{$this->exchange->id}/{$this->symbol}.log";
    }



    public function start()
    {
        $grid_configs = config('antbot.grid_configs');
        $args = [
            $this->exchange->name,
            $this->symbol,
            \Arr::get($grid_configs, $this->grid_mode->value),
            '-lev', $this->leverage,
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
        $pid = \Python::run('passivbot.py', $args, $this->log_path);
        if ($pid > 0) {
            $this->started_at = now();
            $this->pid = $pid;
            $this->save();
        }
    }

    public function stop()
    {
        $success = \Python::kill($this->pid);
        if ($success) {
            $this->started_at = NULL;
            $this->pid = 0;
            $this->save();
        }
    }
}
