<?php

namespace App\Http\Livewire\Admin;

use App\Models\Bot;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use App\Http\Livewire\Traits\LivewireToast;

class AdminCommands extends Component
{
    use LivewireToast;

    public $title = 'Commands';

    public function render()
    {
        return view('livewire.admin.admin-commands')->layoutData([
            'title' => $this->title,
        ]);
    }

    public function emergencyStop()
    {
        $this->toast('Stopping bots... Please wait...');
        Bot::running()->get()->each(function ($item, $key) {
            $item->stop();
            $this->toast("Stopping Bot $item->name");
            logi("Stopping Bot $item->name");
        });
    }

    public function updateAntbot()
    {
        $this->toast('Updating... Please wait...');
        $this->artisanUpdate();
    }

    protected function artisanUpdate()
    {
        Artisan::call('antbot:update');
        $this->toast("Application updated succesfully");
    }

}
