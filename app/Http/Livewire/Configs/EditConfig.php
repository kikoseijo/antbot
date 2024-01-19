<?php

namespace App\Http\Livewire\Configs;

use Livewire\Component;
use App\Models\Config;
use Illuminate\Validation\Rule;

class EditConfig extends Component
{
    use WithValidation;

    public $title = 'Strategy configurations';

    public function render()
    {
        $settings = Config::find(1);
        if ((!$settings->enable_grids || $this->grid->user_id != auth()->user()->id) && !auth()->user()->admin) {
            return abort(403, 'Unauthorized');
        }

        $rederData = $this->renderData();

        return view('livewire.configs.edit-config', $rederData)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        // TODO: delete file if name changed.
        $this->validate();
        $cur_id = $this->grid->id;
        $this->validate([
            'grid.name' => [
                Rule::unique('grids', 'name')->where(function ($query) use ($cur_id ) {
                    return $query
                        ->whereNotIn('id', [$cur_id])
                        ->whereUserId(auth()->user()->id);
                })
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
