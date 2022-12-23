<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use Livewire\Component;

class CreateBot extends Component
{
    use WithValidation;

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.bots.create-bot', $rederData);
    }

    public function mount()
    {
        $this->bot = new Bot;
        $this->clearForm();
    }

    public function clearForm()
    {
        $this->bot->symbol = '';
        $this->bot->market_type = 'futures';
        $this->bot->grid_mode = 'recursive';
        $this->bot->grid_id = '';
        $this->bot->exchange_id = 'bybit';
        $this->bot->assigned_balance = 0;
        $this->bot->lm = 'n';
        $this->bot->lwe = 0.2;
        $this->bot->sm = 'm';
        $this->bot->swe = 0.15;
        $this->bot->leverage = 7;
    }

    public function submit()
    {
        $this->validate();

        $this->bot->user_id = request()->user()->id;
        $this->bot->symbol = strtoupper($this->bot->symbol);
        if($this->bot->grid_id == 'null')
            $this->bot->grid_id = null;
        $this->bot->save();

        session()->flash('message', 'Antbot successfully created.');
        // session()->flash('status', 'bot-created');

        $this->clearForm();

        return redirect()->route('bots.index');
    }
}
