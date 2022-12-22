<?php

namespace App\Http\Livewire\Bots;

use App\Models\Exchange;
use App\Models\Grid;
use App\Models\Bot;

trait WithValidation
{
    public Bot $bot;

    protected $rules = [
        'bot.symbol' => 'required|string|max:12',
        'bot.market_type' => 'required|in:futures,spot',
        'bot.grid_mode' => 'required|string|max:12',
        'bot.exchange_id' => 'required',
        'bot.grid_id' => 'nullable',
        'bot.assigned_balance' => 'required|numeric|between:0,2',
        'bot.leverage' => 'required|numeric|between:1,50',
        'bot.lm' => 'required|in:n,m,gs,t,p',
        'bot.lwe' => 'required|numeric|between:0,10.99',
        'bot.sm' => 'required|in:n,m,gs,t,p',
        'bot.swe' => 'required|numeric|between:0,10.99',
    ];

    protected $validationAttributes = [
        'grid_id' => 'Custom grid',
        'exchange_id' => 'Exchange',
        'lm' => 'Long mode',
        'sm' => 'Short mode',
        'lwe' => 'Long wallet exposure',
        'swe' => 'Short wallet exposure',
    ];

    protected function renderData()
    {
        return [
            'my_exchanges' => Exchange::mine()->orderBy('name')->get(),
            'my_configs' => Grid::mine()->orderBy('name')->get(),
            'grid_modes' => config('antbot.grid_modes'),
            'bot_modes' => config('antbot.bot_modes'),
            'market_types' => config('antbot.market_types')
        ];
    }
}
