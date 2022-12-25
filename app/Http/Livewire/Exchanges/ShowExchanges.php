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
                ->withCount('bots')->mine()->paginate(5),
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
            }
            $this->deleteId = 0;
        }
    }
}
