<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
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
                ->orderBy('name')
                ->mine()
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
            $record = Grid::find($this->deleteId);
            if(auth()->user()->id == $record->user_id){
                $record->delete();
                session()->flash('message', 'Configuration successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }
}
