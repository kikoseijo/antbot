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
    public $sub_title = 'Your Antbots';
    public Exchange $exchange;

    protected $rules = [
        'exchange.id' => 'required',
    ];

    public function mount($exchange = NULL)
    {
        if ($exchange && $exchange->user_id <> auth()->user()->id){
            return abort(403, 'Unauthorized');
        }

        $user_exchanges = auth()->user()->exchanges()->orderBy('name')->get();

        $exchange_count = $user_exchanges->count();
        if ($exchange_count == 0) {
            session()->flash('message', 'Please create your first exchange.');

            return redirect()->route('exchanges.add');
        }

        if ($exchange){
            $this->exchange = $exchange;
        } else {
            if($cur_id = session(CURRENT_EXCHANGE_ID)){
                $current_working_exchange = $user_exchanges->where('id', $cur_id)->first();
                $this->exchange = $current_working_exchange ??  $user_exchanges->first();
            } else {
                $this->exchange = $user_exchanges->first();
            }
        }
        session([CURRENT_EXCHANGE_ID => $this->exchange->id]);
    }



    public function render()
    {
        session([CURRENT_EXCHANGE_ID => $this->exchange->id]);

        $this->sub_title = \Str::upper($this->exchange->name) . ' - Bots';

        $records = $this->exchange->bots()->where('name', 'like', '%'.$this->search.'%')
            ->orderBy(\DB::raw('ISNULL(started_at)'), 'asc')
            ->orderBy('name', 'asc')
            ->mine()
            ->with('exchange', 'grid', 'symbol')
            ->paginate(255);

        $data = [
            'records' => $records,
            'exchanges' => auth()->user()->exchanges->pluck('name', 'id'),
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
            'message' => "Restarting {$bot->name}, please wait..."
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
