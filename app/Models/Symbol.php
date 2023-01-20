<?php

namespace App\Models;

use App\Enums\ExchangesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'exchange' => ExchangesEnum::class,
    ];

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function getNiceNameAttribute()
    {
        return str_replace('_UMCBL', '', $this->name);
    }
}
