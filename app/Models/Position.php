<?php

namespace App\Models;

use App\Enums\ExchangesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($record) {
            foreach($record->orders as $order) {
                $order->delete();
            }
        });
    }

    public function coin()
    {
        return $this->belongsTo(Symbol::class, 'symbol', 'name');
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'position_id');
    }

    public function buy_orders()
    {
        return $this->orders()->where('orders.side', 'Buy');
    }

    public function sell_orders()
    {
        return $this->orders()->where('orders.side', 'Sell');
    }

    public function getExchangeLinkAttribute()
    {
        if ($this->exchange->is_testnet) {
            return match($this->exchange->exchange){
                ExchangesEnum::BYBIT => "https://testnet.bybit.com/trade/usdt/{$this->symbol}",
                ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$this->symbol}",
                default => "#{$this->symbol}",
            };
        } else {
            return match($this->exchange->exchange){
                ExchangesEnum::BYBIT => "https://www.bybit.com/trade/usdt/{$this->symbol}",
                ExchangesEnum::BINANCE => "https://www.binance.com/en/trade/{$this->symbol}",
                default => "#{$this->symbol}",
            };
        }
    }
}
