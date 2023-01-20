<?php

namespace App\Http\Livewire\Bots;

use App\Models\Exchange;
use App\Models\Bot;
use App\Models\Grid;
use App\Models\Symbol;

trait WithValidation
{
    public Bot $bot;

    protected $rules = [
        'bot.name' => 'required|string|max:12',
        'bot.symbol_id' => 'required|exists:symbols,id',
        'bot.market_type' => 'required|in:futures,spot',
        'bot.grid_mode' => 'required|string|max:12',
        'bot.grid_id' => 'nullable',
        'bot.show_logs' => 'sometimes',
        'bot.assigned_balance' => 'required|numeric|between:0,999999',
        'bot.leverage' => 'required|numeric|between:1,50',
        'bot.lm' => 'required|in:n,m,gs,t,p',
        'bot.lwe' => 'required|numeric|between:0,10.99',
        'bot.sm' => 'required|in:n,m,gs,t,p',
        'bot.swe' => 'required|numeric|between:0,10.99',
    ];

    protected $validationAttributes = [
        'grid_id' => 'Custom grid',
        'lm' => 'Long mode',
        'sm' => 'Short mode',
        'lwe' => 'Long wallet exposure',
        'swe' => 'Short wallet exposure',
    ];

    protected $messages = [
        'bot.symbol_id' => 'Only one symbol per exchange allowed.',
        'email.email' => 'The Email Address format is not valid.',
    ];

    protected function renderData()
    {
        return [
            // 'my_exchanges' => Exchange::mine()->orderBy('name')->get(),
            'my_configs' => Grid::mine()->orderBy('name')->get(),
            'grid_modes' => config('antbot.grid_modes'),
            'bot_modes' => config('antbot.bot_modes'),
            'market_types' => config('antbot.market_types')
        ];
    }
}
