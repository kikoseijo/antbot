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
        'exchange.is_testnet' => 'sometimes',
        'exchange.api_error' => 'sometimes',
        // 'exchange.api_frase' => 'sometimes|string|max:250',
    ];

    public function render()
    {
        if ($this->exchange->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized');
        }

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
        $this->exchange->slug = \Str::slug(\Str::squish($this->exchange->name));
        $cur_id = $this->exchange->id;
        $this->validate([
            'exchange.name' => [
                Rule::unique('exchanges', 'name')->where(function ($query) use ($cur_id ) {
                    return $query
                        ->whereNotIn('id', [$cur_id])
                        ->whereUserId(auth()->user()->id);
                })
            ],
        ]);
        if (request()->user()->email <> 'demo@sunnyface.com') {
            $this->exchange->save();
            $res = $this->exchange->user->updateExchangesFile();
            if (auth()->user()->isAdmin()) {
                session()->flash('message', 'File saved into: ' . $res);
            } else {
                session()->flash('message', 'Exchange successfully updated.');
            }
        } else {
            session()->flash('message', 'Success, DEMO MODE ENABLED.');
        }

        session()->flash('status', 'exchange-updated');

        return redirect()->route('exchanges.index');
    }
}
