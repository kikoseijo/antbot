<?php

namespace App\Http\Livewire\Configs;

use Livewire\Component;

class EditConfig extends Component
{
    use WithValidation;

    public function render()
    {
        $rederData = $this->renderData();

        return view('livewire.configs.edit-config', $rederData);
    }

    public function submit()
    {
        $this->validate();
        $this->grid->save();

        session()->flash('status', 'config-updated');
    }
}
