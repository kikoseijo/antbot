<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditBot extends Component
{
    use WithValidation;

    public $title = 'Bots';

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.bots.edit-bot', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->bot->symbol = strtoupper($this->bot->symbol);
        $this->validate([
            'bot.symbol' => [
                Rule::unique('bots', 'symbol')
                    ->ignore(auth()->user()->id)
                    ->ignore($this->bot->id)
            ],
        ]);
        if($this->bot->grid_id == 'null')
            $this->bot->grid_id = null;
        $this->bot->save();

        session()->flash('status', 'bot-updated');
        session()->flash('message', 'Bot Updated Successfully');

        return redirect()->route('bots.index');
    }
}
