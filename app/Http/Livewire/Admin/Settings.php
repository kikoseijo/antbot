<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Config;
use App\Http\Livewire\Traits\LivewireToast;

class Settings extends Component
{
    use LivewireToast;

    public $title = 'Admin Settings';
    public Config $settings;

    protected $rules = [
        'settings.enable_grids' => 'required',
        'settings.enable_what2trade' => 'required',
        'settings.enable_positions' => 'required',
        'settings.enable_routines' => 'required',
        'settings.enable_bots' => 'required',
        'settings.python_path' => 'required|string|max:250',
        'settings.passivbot_path' => 'required|string|max:250',
        'settings.passivbot_logs_path' => 'required|string|max:250',
        'settings.passivbot_grid_neat' => 'required|string|max:250',
        'settings.passivbot_grid_recursive' => 'required|string|max:250',
        'settings.passivbot_grid_clock' => 'required|string|max:250',
        'settings.passivbot_grid_static' => 'required|string|max:250',
        'settings.antbot_branch' => 'required|string|max:250',
        'settings.exchange_max_bots' => 'required|numeric|between:0,999999',
    ];

    public function render()
    {
        $branches = $this->getRemoteBranches();
        return view('livewire.admin.settings', compact('branches'))->layoutData([
            'title' => $this->title,
        ]);
    }

    public function mount()
    {
        $this->settings = Config::find(1);
    }

    public function submit()
    {
        $this->validate();
        $this->settings->save();
        $this->toast("Settings succesfully saved.");
        // return redirect()->route('bots.index', $exc_id);
    }

    protected function getRemoteBranches()
    {
        exec("git branch -r 2>&1", $output);

        return $output;
    }

}
