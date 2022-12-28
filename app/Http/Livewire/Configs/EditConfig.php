<?php

namespace App\Http\Livewire\Configs;

use Livewire\Component;
use Illuminate\Validation\Rule;

class EditConfig extends Component
{
    use WithValidation;

    public $title = 'Grid configurations';

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.configs.edit-config', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $cur_id = $this->grid->id;
        $this->validate([
            'grid.name' => [
                Rule::unique('grids', 'name')->where(function ($query) use ($cur_id ) {

                    return $query
                        ->whereNotIn('id', [$cur_id])
                        ->whereUserId(auth()->user()->id);
                })
                    // ->ignoreModel($this->grid)
                    // ->ignore(auth()->user()->id, 'user_id')
            ],
        ]);
        $this->grid->save();

        $this->grid->saveConfigToDisk();

        if (auth()->user()->isAdmin()) {
            session()->flash('message', 'File saved into: ' . $this->grid->file_path . '.');
        }

        session()->flash('status', 'grid-updated');
    }
}
