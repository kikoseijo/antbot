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
        if ($this->bot->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $rederData = $this->renderData();

        return view('livewire.bots.edit-bot', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->bot->symbol = strtoupper($this->bot->symbol);
        $cur_id = $this->bot->id;
        $this->validate([
            'bot.symbol' => [
                Rule::unique('bots', 'symbol')->where(function ($query) use ($cur_id ) {
                    return $query
                        ->whereNotIn('id', [$cur_id])
                        ->whereUserId(auth()->user()->id);
                })
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
