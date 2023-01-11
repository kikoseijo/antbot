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

    public function createLogsFolder()
    {
        $log_path = config('antbot.paths.logs_path');
        $path = "{$log_path}/{$this->id}";
        if(!\File::isDirectory($path)){
            \File::makeDirectory($path, 0777, true, true);
        }
    }

    public function updateExchangesFile()
    {
        if (\App::environment('local')){
            // The environment is local
            return 'local-mode';
        }

        $configs = new \stdClass();
        foreach ($this->user->exchanges as $exchange) {
            $configs->{$exchange->slug} = [
                    "exchange" => $exchange->exchange->value,
                    "key" => $exchange->api_key,
                    "secret" => $exchange->api_secret
            ];
        }

        $bot_path = config('antbot.paths.bot_path');
        $path = "$bot_path/configs/live/{$this->user->id}";
        $file_name = 'XASPUSDT.json';
        $disk = Storage::build([
            'driver' => 'local',
            'root' => $path,
        ]);
        $disk->put($file_name, json_encode($configs, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));

        return "$path/$file_name";
    }

    public function getFilePath()
    {
        return "configs/live/{$this->user->id}/XASPUSDT.json";
    }

}
