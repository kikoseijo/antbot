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
    public $api_key = '';
    public $api_secret = '';
    public $api_frase = '';

    protected $rules = [
        'exchange.name' => 'required|string',
        'exchange.exchange' => 'required',
        'exchange.risk_mode' => 'required',
        'api_key' => 'required|string|max:100',
        'api_secret' => 'required|string|max:100',
        'exchange.api_error' => 'sometimes',
        'api_frase' => 'sometimes|string|max:250',
        'exchange.is_testnet' => 'sometimes',
    ];

    // protected $appends = ['api_key', 'api_secret', 'api_frase'];

    public function mount()
    {
        $this->rules['exchange.exchange'] = ['required', new Enum(ExchangesEnum::class)];
        $this->rules['exchange.risk_mode'] = ['required', new Enum(ExchangeModeEnum::class)];
        $this->exchange = new Exchange;
        $this->exchange->is_testnet = 0;
        $this->exchange->api_error = 0;

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
                Rule::unique('exchanges', 'name')->where(function ($query) {
                    return $query->whereUserId(auth()->user()->id);
                })
            ],
        ]);

        if (request()->user()->email <> 'demo@sunnyface.com') {
            $this->exchange->user_id = request()->user()->id;
            $this->exchange->api_key = $this->api_key;
            $this->exchange->api_secret = $this->api_secret;
            $this->exchange->api_frase = $this->api_frase;
            $this->exchange->save();
            $this->exchange->createLogsFolder();
            $res = $this->exchange->user->updateExchangesFile();
            session([CURRENT_EXCHANGE_ID => $this->exchange->id]);
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
