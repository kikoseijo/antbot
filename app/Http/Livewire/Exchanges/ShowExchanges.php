<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;

class ShowExchanges extends Component
{
    use WithPagination;
    public $title = 'Exchanges';

    public $search = '';
    public $deleteId = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = [
            'records' => Exchange::where('name', 'like', '%'.$this->search.'%')
                ->withCount('bots', 'positions', 'trades')
                ->with('bots')
                ->mine()
                ->paginate(10),
            'risk_modes' => config('antbot.exchange_mode'),
            'exchanges' => config('antbot.exchanges')
        ];

        return view('livewire.exchanges.show-exchanges', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function truncateLogs(Exchange $exchange)
    {
        $msg = [
            'type' => 'error',
            'message' => 'Something has failed, check logs.'
        ];

        if ($exchange->truncateLogs()) {
            $msg = [
                'type' => 'success',
                'message' => 'All exchange logs have been truncated.'
            ];
        }

        $this->dispatchBrowserEvent('alert', $msg);
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = Exchange::find($this->deleteId);
            if ($record->bots->count() == 0) {
                if(auth()->user()->id == $record->user_id){
                    $record->delete();
                    auth()->user()->updateExchangesFile();
                    session()->flash('message', 'Exchange successfully deleted.');
                    if (session(CURRENT_EXCHANGE_ID) == $this->deleteId){
                        session()->forget(CURRENT_EXCHANGE_ID);
                    }
                }
            } else {
                $this->dispatchBrowserEvent('alert',[
                    'type' => 'error',
                    'message' => "Exchange has Bots.<br />Please remove or dissasociate."
                ]);
            }
            $this->deleteId = 0;
        }
    }

    // protected function updateExchanges()
    // {
    //     $user = auth()->user();
    //     $exchange = $user->exchanges->first();
    //     if ($exchange) {
    //         $res = $user->updateExchangesFile();
    //         if (auth()->user()->isAdmin()) {
    //             session()->flash('message', 'File saved into: ' . $res);
    //         }
    //     } else {
    //         $bot_path = config('antbot.paths.passivbot_path');
    //         unlink("$bot_path/configs/live/{$user->id}/XASPUSDT.json");
    //     }
    // }
}
