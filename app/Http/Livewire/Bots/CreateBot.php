<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use App\Models\Exchange;
use App\Models\Grid;
use Livewire\Component;

class CreateBot extends Component
{
    public $symbol;
    public $market_type = 'futures';
    public $grid_mode = 'recursive';
    public $grid;
    public $exchange = 'bybit';
    public $assigned_balance = 0;
    public $long_mode = 'n';
    public $long_wallet_exposure = 0.2;
    public $short_mode = 'm';
    public $short_wallet_exposure = 0.15;

    protected $rules = [
        'symbol' => 'required|string|max:12',
        'market_type' => 'required|in:futures,spot',
        'grid_mode' => 'required|string|max:12',
        'exchange' => 'required',
        'assigned_balance' => 'required|numeric|between:0,2',
        'long_mode' => 'required|in:n,m,gs,t,p',
        'long_wallet_exposure' => 'required|numeric|between:0,10.99',
        'short_mode' => 'required|in:n,m,gs,t,p',
        'short_wallet_exposure' => 'required|numeric|between:0,10.99',
    ];

    public function render()
    {
        $my_exchanges = Exchange::mine()->orderBy('name')->get();
        $my_configs = Grid::mine()->orderBy('name')->get();
        $grid_modes = config('antbot.grid_modes');
        $bot_modes = config('antbot.bot_modes');
        $market_types = config('antbot.market_types');

        return view('livewire.bots.create-bot',
            compact('my_exchanges', 'my_configs', 'grid_modes', 'bot_modes', 'market_types'));
    }

    public function clearForm()
    {
        $this->symbol;
        $this->market_type = 'futures';
        $this->grid_mode = 'recursive';
        $this->grid;
        $this->exchange = 'bybit';
        $this->assigned_balance = 0;
        $this->long_mode = 'n';
        $this->long_wallet_exposure = 0.2;
        $this->short_mode = 'm';
        $this->short_wallet_exposure = 0.15;
    }

    public function submit()
    {
        $this->validate();

        Bot::create([
            'user_id' => request()->user()->id,
            'symbol' => strtoupper($this->symbol),
            'market_type' => $this->market_type,
            'grid_mode' => $this->grid_mode,
            'grid_id' => $this->grid,
            'exchange_id' => $this->exchange,
            'assigned_balance' => $this->assigned_balance,
            'lm' => $this->long_mode,
            'lwe' => $this->long_wallet_exposure,
            'lm' => $this->short_mode,
            'lwe' => $this->short_wallet_exposure,
        ]);

        session()->flash('message', 'Antbot successfully created.');
        session()->flash('status', 'bot-created');

        $this->clearForm();

        return redirect()->route('bots.index');
    }
}
