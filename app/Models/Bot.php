<?php

namespace App\Models;

use App\Enums\BotModeEnum;
use App\Enums\MarketTypeEnum;
use App\Enums\GridModeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'lm' => BotModeEnum::class,
        'sm' => BotModeEnum::class,
        'market_type' => MarketTypeEnum::class,
        'grid_mode' => GridModeEnum::class,
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grid()
    {
        return $this->belongsTo(Grid::class);
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }

    public function getIsRunningAttribute()
    {
        return $this->started_at && $this->pid > 0;
    }

    public function getLogPathAttribute()
    {
        $log_path = config('antbot.paths.logs_path');

        return "{$log_path}/{$this->exchange->id}/{$this->symbol->name}.log";
    }

    public function isRunning($pid = null)
    {
        $run_pid = $pid > 0 ? $pid : $this->pid;
        $command = 'ps -p ' .  $run_pid;

        exec($command, $op);

        if (!isset($op[1])){
            return false;
        } else {
            return true;
        }
    }

    public function start($force = false)
    {
        if ($this->grid_mode == GridModeEnum::CUSTOM && $this->grid_id > 0) {
            $grid_config = "configs/live/{$this->user_id}/{$this->grid->file_name}";
        } else {
            $grid_config = \Arr::get($grid_configs, $this->grid_mode->value);
        }

        $grid_configs = config('antbot.grid_configs');
        $args = [
            $this->exchange->slug,
            $this->symbol->name,
            $grid_config,
            '-lev', $this->leverage,
            '-lm', $this->lm->value,
            '-lw', $this->lwe,
            '-sm', $this->sm->value,
            '-sw', $this->swe,
            '-ak', $this->exchange->getFilePath(),
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
        if ($success || !$this->isRunning()) {
            $this->started_at = NULL;
            $this->pid = 0;
            $this->save();
        }
    }

    public function restart()
    {
        // TODO: Revisar que no se forme un bucle infinito.
        $pid = $this->pid;
        $this->stop();
        while($this->isRunning($pid)){
            sleep(1);
        }
        $this->start();
    }
}
