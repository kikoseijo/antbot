<?php

namespace App\Http\Livewire\Routines;

use App\Models\Routine;
use App\Models\History;
use Livewire\Component;
use App\Models\Grid;
use App\Http\Livewire\Traits\LivewireToast;

class EditRoutine extends Component
{
    use LivewireToast;
    public Routine $routine;
    public $title = 'Routines';

    protected $rules = [
        'routine.name' => 'required|string',
        'routine.type' => 'required',
        // 'routine.end_scheduled_at' => 'sometimes|date',
        // 'routine.bot_group_id' => 'sometimes|exists:bot_groups,id',
        'routine.action.grid_mode' => 'required',
        'routine.action.grid_id' => 'required',
        'routine.action.lm' => 'required|in:n,m,gs,t,p',
        'routine.action.lwe' => 'required|numeric|between:0,10.99',
        'routine.action.sm' => 'required|in:n,m,gs,t,p',
        'routine.action.swe' => 'required|numeric|between:0,10.99',
    ];

    public function render()
    {
        if ($this->routine->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized');
        }
        $rederData = [
            'my_configs' => Grid::mine()->orderBy('name')->get(),
            'grid_modes' => config('antbot.grid_modes'),
            'bot_modes' => config('antbot.bot_modes'),
        ];
        return view('livewire.routines.edit-routine', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->routine->save();

        // $new_history = (new History)->forResourceUpdate(auth()->user(), $this->bot);
        // $new_history->save();

        return redirect()->route('routines.index');
    }
}
