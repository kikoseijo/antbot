<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CreateConfig extends Component
{
    use WithValidation;

    public $title = 'Grid configurations';

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.configs.create-config', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function mount()
    {
        $this->grid = new Grid;
        $this->clearForm();
    }

    public function clearForm()
    {
        $this->grid->name = '';
        $this->grid->description = '';
        $this->grid->grid_json = '';
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'grid.name' => [
                Rule::unique('grids', 'name')
                    ->ignore(auth()->user()->id)
            ],
        ]);
        $this->grid->user_id = request()->user()->id;
        $this->grid->save();

        $this->grid->saveConfigToDisk();

        session()->flash('message', 'Configuration successfully created.');
        // session()->flash('status', 'config-created');

        $this->clearForm();

        return redirect()->route('configs.index');
    }
}
