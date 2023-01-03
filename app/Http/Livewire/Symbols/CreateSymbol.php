<?php

namespace App\Http\Livewire\Symbols;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class CreateSymbol extends Component
{
    public Symbol $symbol;
    public $title = 'Symbols';

    protected $rules = [
        'symbol.name' => 'required|string',
        'symbol.exchange' => 'required',
        'symbol.market' => 'required',
    ];

    public function mount()
    {
        $this->rules['symbol.exchange'] = ['required', new Enum(ExchangesEnum::class)];
        $this->symbol = new Symbol;
    }

    public function render()
    {
        $data = [
            'market_types' => config('antbot.market_types'),
            'exchanges' => config('antbot.exchanges')
        ];

        return view('livewire.symbols.create-symbol', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function clearForm()
    {
        $this->symbol = new Symbol;
    }

    public function submit()
    {
        $this->validate();
        $this->symbol->status = 'Trading';
        $this->symbol->save();


        session()->flash('message', 'Exchange successfully created.');
        session()->flash('status', 'exchange-created');

        $this->clearForm();

        return redirect()->route('symbols.index');
    }
}
