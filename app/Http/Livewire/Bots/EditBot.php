<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use App\Models\Symbol;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditBot extends Component
{
    use WithValidation;

    public $title = 'Bots';

    public function render()
    {
        if ($this->bot->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized');
        }

        $rederData = $this->renderData();
        $rederData['symbols'] = Symbol::where('exchange', $this->bot->exchange->exchange->value)
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');

        return view('livewire.bots.edit-bot', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $cur_id = $this->bot->id;
        $exc_id = $this->bot->exchange_id;
        $this->validate([
            'bot.symbol_id' => [
                Rule::unique('bots', 'symbol_id')->where(function ($query) use ($cur_id, $exc_id) {
                    return $query
                        ->whereNotIn('id', [$cur_id])
                        ->whereExchangeId($exc_id)
                        ->whereUserId(auth()->user()->id);
                })
            ],
        ]);
        if($this->bot->grid_id == 'null')
            $this->bot->grid_id = null;
        $this->bot->save();

        session()->flash('status', 'bot-updated');
        session()->flash('message', 'Bot Updated Successfully');

        return redirect()->route('bots.index', $exc_id);
    }
}
