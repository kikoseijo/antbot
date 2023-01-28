<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use App\Http\Livewire\Traits\LivewireToast;

class AutoUpdater extends Component
{
    use LivewireToast;

    public $title = 'Commands';

    public function render()
    {
        return view('livewire.admin.auto-updater')->layoutData([
            'title' => $this->title,
        ]);
    }

    public function updateAntbot()
    {
        Artisan::call('antbot:update');
        $this->toast("Application updated succesfully");
    }

}
