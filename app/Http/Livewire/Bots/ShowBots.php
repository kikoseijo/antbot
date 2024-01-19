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
    public $title = 'Bots';
    public $sub_title = 'Your Bots';


    public function mount()
    {
        if (!auth()->user()->exchange_id) {
            session()->flash('message', 'Please create your first exchange.');

            return redirect()->route('exchanges.add');
        }
    }

    public function render()
    {

        $cur_exchange = auth()->user()->exchange;
        $this->sub_title = \Str::upper($cur_exchange->name);

        $records = $cur_exchange->bots()->where('name', 'like', '%'.$this->search.'%')
            ->withAggregate('symbol','name')
            ->orderBy(\DB::raw('ISNULL(started_at)'), 'asc')
            ->orderBy('symbol_name', 'asc')
            ->mine()
            ->with('exchange', 'grid', 'symbol')
            ->paginate(255);

        $data = [
            'records' => $records,
            'bot_modes' => config('antbot.bot_modes')
        ];

        return view('livewire.bots.show-bots', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function duplicateBot(Bot $bot)
    {
        $newBot = $bot->replicate();
        $newBot->name = $bot->name . ' copy';
        $newBot->created_at = now();
        $newBot->started_at = NULL;
        $newBot->symbol_id = 0; // To avoid running 2 bots on same pair.
        $newBot->pid = NULL;
        $newBot->save();

        session()->flash('message', 'Bot duplication successfull.');
    }

    public function changeBotStatus(Bot $bot)
    {
        $this->dispatchBrowserEvent('alert',[
            'type' => 'info',
            'message' => "Executing task for {$bot->name}, please wait..."
        ]);
        if ($bot->is_running) {
            $bot->stop();
        } else {
            $bot->start();
        }
    }

    public function restartBot(Bot $bot)
    {
        $this->dispatchBrowserEvent('alert',[
            'type' => 'info',
            'message' => "{$bot->name} - restarting, please wait..."
        ]);
        $bot->restart();
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $bot = Bot::find($this->deleteId);
            if (!$bot->is_running) {
                if(auth()->user()->id == $bot->user_id){
                    $bot->delete();
                    session()->flash('message', 'Bot successfully deleted.');
                }
                $this->deleteId = 0;
            } else {
                $this->dispatchBrowserEvent('alert',[
                    'type' => 'error',
                    'message' => "Can't delete a bot that it's running."
                ]);
            }
        }
    }
}
