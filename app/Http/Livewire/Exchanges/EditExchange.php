<?php

namespace App\Http\Livewire\Exchanges;

use App\Enums\ExchangeModeEnum;
use App\Enums\ExchangesEnum;
use App\Models\Exchange;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class EditExchange extends Component
{
    public Exchange $exchange;
    public $title = 'Exchanges';

    protected $rules = [
        'exchange.name' => 'required|string',
        'exchange.exchange' => 'required',
        'exchange.risk_mode' => 'required',
    ];

    public function render()
    {
        $this->rules['exchange.exchange'] = ['required', new Enum(ExchangesEnum::class)];
        $this->rules['exchange.risk_mode'] = ['required', new Enum(ExchangeModeEnum::class)];
        $data = [
            'risk_modes' => config('antbot.exchange_mode'),
            'exchanges' => config('antbot.exchanges')
        ];

        return view('livewire.exchanges.edit-exchange', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->exchange->name = \Str::snake($this->exchange->name);
        $this->exchange->save();

        session()->flash('status', 'exchange-updated');
    }
}
