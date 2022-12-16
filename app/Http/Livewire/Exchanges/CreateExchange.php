<?php

namespace App\Http\Livewire\Exchanges;

use Livewire\Component;
use App\Models\Exchange;

class CreateExchange extends Component
{
    public $name;
    public $exchange;
    public $api_key;
    public $api_secret;

    protected $rules = [
        'name' => 'required|min:6',
        'exchange' => 'required|max:12',
        'api_key' => 'required|max:100',
        'api_secret' => 'required|max:100',
    ];

    public function render()
    {
        return view('livewire.exchanges.create-exchange');
    }

    public function submit()
    {
        $this->validate();

        Exchange::create([
            'user_id' => request()->user()->id,
            'name' => $this->name,
            'exchange' => $this->exchange,
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
        ]);

        session()->flash('message', 'Exchange successfully created.');

        return redirect()->route('exchanges.index');
    }
}
