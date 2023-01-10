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
                ->withCount('bots')->with('bots')->mine()->paginate(5),
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

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = Exchange::find($this->deleteId);
            if(auth()->user()->id == $record->user_id){
                $record->delete();
                session()->flash('message', 'Exchange successfully deleted.');
                $this->updateExchangeFile();
            }
            $this->deleteId = 0;
        }
    }

    protected function updateExchangeFile()
    {
        $user = auth()->user();
        $exchange = $user->exchanges->first();
        if ($exchange) {
            $res = $exchange->updateExchangesFile();
            if (auth()->user()->isAdmin()) {
                session()->flash('message', 'File saved into: ' . $res);
            }
        } else {
            $bot_path = config('antbot.paths.bot_path');
            unlink("$bot_path/configs/live/{$user->id}/XASPUSDT.json");
        }
    }
}
