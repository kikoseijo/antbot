<?php

namespace App\Models;

use App\Enums\ExchangesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exchange extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'exchange' => ExchangesEnum::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($record) {

            // We dont need logs anymore.
            Storage::deleteDirectory($record->logs_path);

            foreach ($record->bots as $bot) {
                $bot->stop();
                $bot->delete();
            }
            foreach ($record->balances as $balance) {
                $balance->delete();
            }

            foreach ($record->trades as $trade) {
                $trade->delete();
            }

            foreach ($record->positions as $position) {
                $position->delete();
            }
        });
    }

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function running_bots()
    {
        return $this->hasMany(Bot::class)
            ->where('pid', '>', 0)
            ->whereNotNull('started_at');
    }

    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLongWalletExposureAttribute()
    {
        $level = $this->bots->sum('lwe');

        return $level;
    }

    public function getShortWalletExposureAttribute()
    {
        $level = $this->bots->sum('swe');

        return $level;
    }

    public function getMaxExposureAttribute()
    {
        return match($this->risk_mode){
            2 => 2.5,
            3 => 5,
            default => 1.8,
        };
    }

    public function hasRunningBotsForSymbol($symbol_name)
    {
        $symbol_id = Symbol::whereName($symbol_name)->pluck('id');
        if ($symbol_id->count() > 0){
            $running_bot = collect($this->running_bots)->where('symbol_id', $symbol_id[0]);
            if ($running_bot->count() > 0)
                return true;
        }
        return false;
    }

    public function truncateLogs()
    {
        $command = "truncate -s 0 {$this->log_path}/*log";

        exec($command, $op);

        if (!isset($op[1])){
            return false;
        } else {
            return true;
        }
    }

    public function getLogsPathAttribute()
    {
        $log_path = config('antbot.paths.logs_path');
        return "{$log_path}/{$this->id}";
    }

    public function createLogsFolder()
    {
        $path = $this->logs_path;
        if(!\File::isDirectory($path)){
            \File::makeDirectory($path, 0777, true, true);
        }
    }

    public function getFilePath()
    {
        return "configs/live/{$this->user->id}/XASPUSDT.json";
    }

}
