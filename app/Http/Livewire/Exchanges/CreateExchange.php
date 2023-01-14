<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Enums\ExchangeModeEnum;
use App\Enums\ExchangesEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateExchange extends Component
{
    public Exchange $exchange;
    public $title = 'Exchanges';

    protected $rules = [
        'exchange.name' => 'required|string',
        'exchange.exchange' => 'required',
        'exchange.risk_mode' => 'required',
        'exchange.api_key' => 'required|string|max:100',
        'exchange.api_secret' => 'required|string|max:100',
        'exchange.api_frase' => 'sometimes|string|max:250',
        'exchange.is_testnet' => 'sometimes',
    ];

    public function mount()
    {
        $this->rules['exchange.exchange'] = ['required', new Enum(ExchangesEnum::class)];
        $this->rules['exchange.risk_mode'] = ['required', new Enum(ExchangeModeEnum::class)];
        $this->exchange = new Exchange;
        $this->exchange->is_testnet = 0;
    }

    public function render()
    {
        $data = [
            'risk_modes' => config('antbot.exchange_mode'),
            'exchanges' => config('antbot.exchanges')
        ];

        return view('livewire.exchanges.create-exchange', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function clearForm()
    {
        $this->exchange = new Exchange;
    }

    public function submit()
    {
        $this->validate();
        $this->exchange->slug = \Str::slug(\Str::squish($this->exchange->name));
        $this->validate([
            'exchange.name' => [
                Rule::unique('exchanges', 'name')
                    ->ignore(auth()->user()->id, 'user_id')
            ],
        ]);

        if (request()->user()->email <> 'demo@sunnyface.com') {
            $this->exchange->user_id = request()->user()->id;
            $this->exchange->save();
            $this->exchange->createLogsFolder();
            $res = $this->exchange->user->updateExchangesFile();
            session()->flash('message', 'Exchange successfully created.');
        } else {
            session()->flash('message', 'Action can`t be done, DEMO MODE ENABLED.');
        }

        if (auth()->user()->isAdmin()) {
            session()->flash('message', 'File saved into: ' . $res);
        }
        session()->flash('status', 'exchange-created');

        $this->clearForm();

        return redirect()->route('exchanges.index');
    }
}
