<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use App\Models\Bot;
use App\Models\Config;
use Livewire\Component;
use Livewire\WithPagination;

class ShowConfigs extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = 0;
    public $title = 'Strategy configurations';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $settings = Config::find(1);
        if (!$settings->enable_grids && !auth()->user()->admin) {
            return abort(403, 'Unauthorized');
        }

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

    public function duplicateGrid(Grid $grid)
    {
        $newGrid = $grid->replicate();
        $newGrid->name = $grid->name . ' copy';
        $newGrid->created_at = now();
        $newGrid->save();

        session()->flash('message', 'Grid duplication successfull.');
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
