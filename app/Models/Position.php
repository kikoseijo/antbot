<?php

namespace App\Models;

use App\Enums\ExchangesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getExchangeLinkAttribute()
    {
        return match($this->exchange->exchange){
            ExchangesEnum::BYBIT => "https://www.bybit.com/trade/usdt/{$this->symbol}",
            ExchangesEnum::BINANCE => "#{$this->symbol}",
            default => "#{$this->symbol}",
        };
    }
}
