<?php

namespace App\Http\Livewire\Routines;

use App\Models\Routine;
use App\Http\Livewire\Traits\LivewireToast;
use Livewire\Component;
use Livewire\WithPagination;


class ShowRoutines extends Component
{
    use WithPagination, LivewireToast;
    public $title = 'Routines';

    public $search = '';
    public $deleteId = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = [
            'records' => Routine::where('name', 'like', '%'.$this->search.'%')
                ->mine()
                ->orderBy('triggered_at', 'desc')
                // ->with('grid')
                ->paginate(25),
        ];

        return view('livewire.routines.show-routines', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function duplicateRoutine(Routine $routine)
    {
        $newRoutine = $routine->replicate();
        $newRoutine->name = $routine->name . ' copy';
        $newRoutine->created_at = now();
        $newRoutine->triggered_at = NULL;
        $newRoutine->triggered_by = NULL;
        $newRoutine->save();

        $this->toast('Routine duplication successfull.');
    }

    public function runRoutine()
    {
        // logi($this->deleteId);
        if (!is_null($this->deleteId)) {
            $triggered_by = 'user:' . auth()->user()->id;
            $sleep_time = 1;
            $routine = Routine::find($this->deleteId);
            $routine->execute($triggered_by, $sleep_time);
            $this->toast("Routine executed succesfully.");
            $this->deleteId = 0;
        } else {
            $this->toast("No id, cant run action.");
        }
    }

    public function runId($id)
    {
        $this->deleteId = $id;
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = Routine::find($this->deleteId);
            if(auth()->user()->id == $record->user_id){
                $record->delete();
                $this->toast('message', 'Routine successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }
}
