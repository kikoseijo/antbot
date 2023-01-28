<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use App\Models\Exchange;
use App\Models\Symbol;
use App\Rules\BotLimits;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CreateBot extends Component
{
    use WithValidation;

    public Exchange $exchange;
    public $title = 'Bots';
    public $bot_limits;

    public function render()
    {
        $exchange_count = auth()->user()->exchanges()->count();
        if ($exchange_count == 0) {
            session()->flash('message', 'You must create an exchange before a Bot.');

            redirect(route('exchanges.add'));
        }
        $rederData = $this->renderData();

        $rederData['symbols'] = Symbol::where('exchange', $this->exchange->exchange->value)
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');

        return view('livewire.bots.create-bot', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function mount()
    {
        $this->bot = new Bot;
        $this->bot->show_logs = 0;
        $this->clearForm();
        $this->rules['bot_limits'] = 'bot_limits';
    }

    public function clearForm()
    {
        $this->bot->name = '';
        $this->bot->show_logs = 0;
        $this->bot->symbol_id = '';
        $this->bot->market_type = 'futures';
        $this->bot->grid_mode = 'recursive';
        $this->bot->grid_id = '';
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
        $exc_id = $this->exchange->id;
        $this->bot->exchange_id = $exc_id;
        $this->validate([
            'bot.symbol_id' => [
                Rule::unique('bots', 'symbol_id')->where(function ($query) use ($exc_id) {
                    return $query
                        ->whereExchangeId($exc_id)
                        ->whereUserId(auth()->user()->id);
                })
            ],
            'bot_limits' => [new BotLimits],
        ]);
        $this->bot->user_id = request()->user()->id;
        if($this->bot->grid_id == 'null' || $this->bot->grid_id == '')
            $this->bot->grid_id = null;
        $this->bot->save();

        session()->flash('message', "{$this->bot->name} created successfully.");
        // session()->flash('status', 'bot-created');

        $this->clearForm();

        return redirect()->route('bots.index', $exc_id);
    }
}
