<?php

namespace App\Http\Livewire\Bots;

use App\Models\Bot;
use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;

class ShowBots extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $records = Bot::where('symbol', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->mine()
            ->with('exchange', 'grid')
            ->paginate(10);

        return view('livewire.bots.show-bots', [
            'records' => $records
        ]);
    }

    public function changeBotStatus(Bot $bot)
    {
        \Log::info($bot->started_at . ' PID: ' . $bot->pid);
        if ($bot->started_at && $bot->pid > 0) {
            $bot->started_at = NULL;
            $bot->pid = 0;
        } else {
            // dd($bot);
            $grid_configs = config('antbot.grid_configs');
            // dd($grid_configs);
            $exchange = Exchange::find($bot->exchange_id);
            $args = [
                $exchange->name,
                $bot->symbol,
                \Arr::get($bot->grid_mode, $grid_configs),
                '-m', $bot->market_type,
                '-ab', $bot->assigned_balance,
                '-lm', $bot->lm,
                '-lw', $bot->lwe,
                '-sm', $bot->sm,
                '-sw', $bot->swe,
            ];
            $pid = \Python::run('/home/antbot/passivbotpassivbot.py', $args);
            \Log::info($bot->symbol . ' PID: ' . $pid);
            $bot->started_at = now();

            $bot->pid = $pid;
        }
        $bot->save();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = Bot::find($this->deleteId);
            if(auth()->user()->id == $record->user_id){
                $record->delete();
                session()->flash('message', 'Bot successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }
}
