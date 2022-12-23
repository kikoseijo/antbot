<?php

namespace App\Http\Livewire\Exchanges;

use Livewire\Component;
use App\Models\Exchange;

class CreateExchange extends Component
{
    public Exchange $exchange;
    public $title = 'Exchanges';

    protected $rules = [
        'exchange.name' => 'required|string',
        'exchange.exchange' => 'required|string|max:12',
        'exchange.api_key' => 'required|string|max:100',
        'exchange.api_secret' => 'required|string|max:100',
    ];

    public function mount()
    {
        $this->exchange = new Exchange;
    }

    public function render()
    {
        return view('livewire.exchanges.create-exchange')->layoutData([
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

        $this->exchange->name = Str::snake($this->exchange->name);
        $this->exchange->user_id = request()->user()->id;
        $this->exchange->save();

        // session()->flash('message', 'Exchange successfully created.');
        session()->flash('status', 'exchange-created');

        $this->clearForm();

        // return redirect()->route('exchanges.index');
    }
}
