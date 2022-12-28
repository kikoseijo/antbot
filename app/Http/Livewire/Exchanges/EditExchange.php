<?php

namespace App\Http\Livewire\Exchanges;

use App\Enums\ExchangeModeEnum;
use App\Enums\ExchangesEnum;
use App\Models\Exchange;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
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
        $this->exchange->name = \Str::slug(\Str::squish($this->exchange->name));
        $this->validate([
            'exchange.name' => [
                Rule::unique('exchanges', 'name')
                    ->ignore($this->exchange->id)
                    ->ignore(auth()->user()->id)
            ],
        ]);
        $this->exchange->save();

        $res = $this->exchange->updateExchangesFile();
        if (auth()->user()->isAdmin()) {
            session()->flash('message', 'File saved into: ' . $res);
        } else {
            session()->flash('message', 'Exchange successfully updated.');
        }
        session()->flash('status', 'exchange-updated');

        return redirect()->route('exchanges.index');
    }
}
