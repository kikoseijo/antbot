<?php

namespace App\Http\Livewire\Routines;

use Livewire\Component;
use App\Models\Routine;
use App\Models\History;
use App\Models\Grid;
use App\Http\Livewire\Traits\LivewireToast;

class CreateRoutine extends Component
{
    use LivewireToast;
    public Routine $routine;
    public $title = 'Routines';

    protected $rules = [
        'routine.name' => 'required|string',
        'routine.type' => 'required',
        // 'routine.end_scheduled_at' => 'sometimes',
        // 'routine.bot_group_id' => 'sometimes|exists:bot_groups,id',
        'routine.action.grid_mode' => 'required',
        'routine.action.grid_id' => 'required',
        'routine.action.lm' => 'required|in:n,m,gs,t,p',
        'routine.action.lwe' => 'required|numeric|between:0,11',
        'routine.action.sm' => 'required|in:n,m,gs,t,p',
        'routine.action.swe' => 'required|numeric|between:0,11',
    ];

    public function mount()
    {
        $this->routine = new Routine;
        $this->routine->name = 'New strategy';
        $this->routine->type = 'passivbot';
    }

    public function render()
    {
        $rederData = [
            'my_configs' => Grid::mine()->orderBy('name')->get(),
            'grid_modes' => config('antbot.grid_modes'),
            'bot_modes' => config('antbot.bot_modes'),
        ];
        return view('livewire.routines.create-routine', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function clearForm()
    {
        $this->routine = new Routine;
    }

    public function submit()
    {
        $this->validate();
        $this->routine->user_id = auth()->user()->id;
        if (!is_null($this->routine->end_scheduled_at)) {
            $this->is_end_scheduled = 1;
        }
        $this->routine->save();

        // $new_history = (new History)->forResourceCreate(auth()->user(), $this->routine);
        // $new_history->save();

        $this->toast('Routine successfully created.');
        sleep(1);

        $this->clearForm();

        return redirect()->route('routines.index');
    }
}
