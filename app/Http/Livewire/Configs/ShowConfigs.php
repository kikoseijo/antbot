<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use App\Models\Bot;
use Livewire\Component;
use Livewire\WithPagination;

class ShowConfigs extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = 0;
    public $title = 'Grid configurations';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.configs.show-configs', [
            'records' => Grid::where('name', 'like', '%'.$this->search.'%')
                ->mine()
                ->withCount('running_bots')
                ->orderBy('name')
                ->paginate(20)
        ])->layoutData([
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
            $bots_using_grid_count = Bot::where('grid_id', $this->deleteId)->count();
            if ($bots_using_grid_count == 0) {
                $record = Grid::find($this->deleteId);
                if(auth()->user()->id == $record->user_id){
                    $record->delete();
                    session()->flash('message', 'Configuration successfully deleted.');
                }
            } else {
                $this->dispatchBrowserEvent('alert',[
                    'type' => 'error',
                    'message' => "Can not delete grid that its used by a bot."
                ]);
            }
            $this->deleteId = 0;
        }
    }
}
