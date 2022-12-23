<?php

namespace App\Http\Livewire\Configs;

use Livewire\Component;

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
        $this->grid->save();

        $this->grid->saveConfigToDisk();

        if (auth()->user()->isAdmin()) {
            session()->flash('message', 'File saved into: ' . $this->grid->file_path . '.');
        }

        session()->flash('status', 'grid-updated');
    }
}
