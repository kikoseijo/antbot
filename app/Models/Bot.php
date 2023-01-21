<?php

namespace App\Models;

use App\Enums\BotModeEnum;
use App\Enums\ExchangesEnum;
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

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($record) {
            // We dont need logs anymore.
            if (\File::exists($record->log_path)) {
                 \File::delete($record->log_path);
             }
        });
    }

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
        $log_path = config('antbot.paths.passivbot_logs');
        $file_name = $this->symbol->nice_name ?? 'XXX12XX';
        return "{$log_path}/{$this->exchange->id}/{$file_name}.log";
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
        $grid_configs = config('antbot.grid_configs');
        if ($this->grid_mode == GridModeEnum::CUSTOM && $this->grid_id > 0) {
            $grid_config = "configs/live/{$this->user_id}/{$this->grid->file_name}";
        } else {
            $grid_config = \Arr::get($grid_configs, $this->grid_mode->value);
        }

        $args = [
            $this->exchange->slug,
            $this->symbol->nice_name,
            $grid_config,
            '-lev', $this->leverage,
            '-lm', $this->lm->value,
            '-lw', $this->lwe,
            '-sm', $this->sm->value,
            '-sw', $this->swe,
            '-ak', $this->exchange->getFilePath(),
        ];
        if ($this->exchange->is_testnet) {
            $args = array_merge($args, ['-tm']);
        }
        if ($this->assigned_balance != 0) {
            $args = array_merge($args, ['-ab', $this->assigned_balance]);
        }
        if ($this->market_type != MarketTypeEnum::FUTURES) {
            $args = array_merge($args, ['-m', $this->market_type->value]);
        }

        $logs_file = $this->show_logs ? $this->log_path : '/dev/null';

        // logi('$args');
        $pid = \Python::run('passivbot.py', $args, $logs_file);
        if ($pid > 0) {
            $this->started_at = now();
            $this->pid = $pid;
            $this->save();
        }
    }

    public function stop()
    {
        $success = false;
        if ($this->pid > 0) {
            $success = \Python::kill($this->pid);
        }

        if ($success || !$this->isRunning()) {
            $this->started_at = NULL;
            $this->pid = NULL;
            $this->save();
        }
    }

    public function getExchangeLinkAttribute()
    {
        $symbol = optional($this->symbol)->name;
        if ($this->market_type === MarketTypeEnum::FUTURES ) {
            if ($this->exchange->is_testnet) {
                return match($this->exchange->exchange){
                    ExchangesEnum::BYBIT => "https://testnet.bybit.com/trade/usdt/{$symbol}",
                    ExchangesEnum::BITGET => "https://www.bitget.com/en/mix/usdt/{$symbol}",
                    ExchangesEnum::OKX => "https://www.okx.com/trade-swap/{$symbol}-swap",
                    ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$symbol}?theme=dark&type=cross",
                    default => "#{$symbol}",
                };
            } else {
                return match($this->exchange->exchange){
                    ExchangesEnum::BYBIT => "https://www.bybit.com/trade/usdt/{$symbol}",
                    ExchangesEnum::BITGET => "https://www.bitget.com/en/mix/usdt/{$symbol}",
                    ExchangesEnum::OKX => "https://www.okx.com/trade-swap/{$symbol}-swap",
                    ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$symbol}?theme=dark&type=cross",
                    default => "#{$symbol}",
                };
            }

        } else {
            if ($this->exchange->is_testnet) {
                return match($this->exchange->exchange){
                    ExchangesEnum::BYBIT => "https://testnet.bybit.com/en-US/trade/spot/{$this->symbol->base_currency}/{$this->symbol->quote_currency}",
                    ExchangesEnum::BITGET => "https://www.bitget.com/en/mix/usdt/{$symbol}",
                    ExchangesEnum::OKX => "https://www.okx.com/trade-spot/{$symbol}",
                    ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$symbol}?theme=dark&type=spot",
                    default => "#{$symbol}",
                };
            } else {
                return match($this->exchange->exchange){
                    ExchangesEnum::BYBIT => "https://www.bybit.com/en-US/trade/spot/{$this->symbol->base_currency}/{$this->symbol->quote_currency}",
                    ExchangesEnum::BITGET => "https://www.bitget.com/en/mix/usdt/{$symbol}",
                    ExchangesEnum::OKX => "https://www.okx.com/trade-spot/{$symbol}",
                    ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$symbol}?theme=dark&type=spot",
                    default => "#{$symbol}",
                };
            }
        }
    }

    public function restart()
    {
        $pid = $this->pid;

        $this->stop();

        while($this->isRunning($pid)){
            sleep(1);
        }

        $this->start();
    }
}
