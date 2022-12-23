<?php

namespace App\Http\Livewire\Exchanges;

use Livewire\Component;
use App\Models\Exchange;

class EditExchange extends Component
{
    public Exchange $exchange;
    public $title = 'Exchanges';

    protected $rules = [
        'exchange.name' => 'required|string',
        'exchange.exchange' => 'required|string|max:12',
    ];

    // $table->unsignedInteger('user_id')->index();
    // $table->string('name');
    // $table->string('exchange', 12);
    // $table->string('api_key', 100);
    // $table->string('api_secret', 100);

    public function render()
    {
        return view('livewire.exchanges.edit-exchange')->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->exchange->name = Str::snake($this->exchange->name);
        $this->exchange->save();

        session()->flash('status', 'exchange-updated');
    }
}
