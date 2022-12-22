<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use Livewire\Component;

class CreateConfig extends Component
{
    use WithValidation;

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.configs.create-config', $rederData);
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

        $this->grid->user_id = request()->user()->id;
        $this->grid->save();

        session()->flash('message', 'Configuration successfully created.');
        // session()->flash('status', 'config-created');

        $this->clearForm();

        return redirect()->route('configs.index');
    }
}
