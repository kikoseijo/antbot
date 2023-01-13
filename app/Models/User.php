<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthenticationLoggable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function exchanges()
    {
        return $this->hasMany(Exchange::class);
    }

    public function grids()
    {
        return $this->hasMany(Grid::class);
    }

    public function isAdmin()
    {
        return $this->admin && $this->role == 1;
    }

    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    public function canBeImpersonated()
    {
        return $this->role != 1;
    }

    public function getConfigsFolderAttribute()
    {
        $bot_path = config('antbot.paths.bot_path');
        return "$bot_path/configs/live/{$this->id}";
    }

    public function updateExchangesFile()
    {
        if (\App::environment('local')){
            // The environment is local
            return 'local-mode';
        }

        $configs = new \stdClass();
        foreach ($this->exchanges as $exchange) {
            $configs->{$exchange->slug} = [
                    "exchange" => $exchange->exchange->value,
                    "key" => $exchange->api_key,
                    "secret" => $exchange->api_secret
            ];
        }

        $path = $this->configs_folder;
        $file_name = 'XASPUSDT.json';
        $disk = Storage::build([
            'driver' => 'local',
            'root' => $path,
        ]);
        $disk->put($file_name, json_encode($configs, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));

        return "$path/$file_name";
    }

    public function destroyResources()
    {
        foreach ($this->grids as $grid) {
            $grid->delete();
        }
        Storage::deleteDirectory($this->configs_folder);

        foreach ($this->exchanges as $exchange) {
            // Bots gets deleted by exchange.
            // Same with balances and trades.
            $exchange->delete();
        }

    }

    public function notifyAuthenticationLogVia()
    {
        return ['mail'];
        return ['nexmo', 'mail', 'slack'];
    }
}
