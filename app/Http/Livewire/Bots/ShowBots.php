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

        $data = [
            'records' => $records,
            'bot_modes' => config('antbot.bot_modes')
        ];

        // $stats = $this->getStats($records);

        return view('livewire.bots.show-bots', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function changeBotStatus(Bot $bot)
    {
        // \Log::info($bot->started_at . ' PID: ' . $bot->pid);
        if ($bot->is_running) {
            $bot->stop();
        } else {
            $bot->start();
        }
    }

    public function restartBot(Bot $bot)
    {
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

    public function changeStatus()
    {

    }

    protected function getStats($bots)
    {
        $res = [];
        $exchanges = [];
        $total_wallet_exposure_short = 0;
        $total_wallet_exposure_long = 0;
        $total_wallet_exposure_short_on = 0;
        $total_wallet_exposure_long_on = 0;
        $count = [];
        $total_running = [];
        foreach ($bots as $bot) {
            $total_wallet_exposure_long += $bot->lwe;
            $total_wallet_exposure_short += $bot->swe;
            if ($bot->is_running()) {
                if ($record->sm->value != 'm') {
                    $total_wallet_exposure_short_on += $bot->lwe;
                }
                if ($record->lm->value != 'm') {
                    $total_wallet_exposure_long_on += $bot->lwe;
                }
            }
            $stats = [

            ];
        }
    }
}
