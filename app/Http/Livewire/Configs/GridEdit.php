<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use Livewire\Component;

class GridEdit extends Component
{
    public Grid $grid;
    public array $l_grid;
    public array $s_grid;
    public $title = 'Grid visual editor';

    protected $rules = [

        'grid.name' => 'required|string|max:12',
        'grid.description' => 'sometimes|string',

        'l_grid.auto_unstuck_ema_dist' => 'required|number',
        'l_grid.auto_unstuck_wallet_exposure_threshold' => 'required|number',
        'l_grid.backwards_tp' => 'required|number',
        'l_grid.ddown_factor' => 'required|number',
        'l_grid.ema_span_0' => 'required|number',
        'l_grid.ema_span_1' => 'required|number',
        'l_grid.enabled' => 'required',
        'l_grid.initial_eprice_ema_dist' => 'required|number',
        'l_grid.initial_qty_pct' => 'required|number',
        'l_grid.markup_range' => 'required|number',
        'l_grid.min_markup' => 'required|number',
        'l_grid.n_close_orders' => 'required|number',
        'l_grid.rentry_pprice_dist' => 'required|number',
        'l_grid.rentry_pprice_dist_wallet_exposure_weighting' => 'required|number',
        'l_grid.wallet_exposure_limit' => 'required|number',

        's_grid.auto_unstuck_ema_dist' => 'required|number',
        's_grid.auto_unstuck_wallet_exposure_threshold' => 'required|number',
        's_grid.backwards_tp' => 'required|number',
        's_grid.ddown_factor' => 'required|number',
        's_grid.ema_span_0' => 'required|number',
        's_grid.ema_span_1' => 'required|number',
        's_grid.enabled' => 'required',
        's_grid.initial_eprice_ema_dist' => 'required|number',
        's_grid.initial_qty_pct' => 'required|number',
        's_grid.markup_range' => 'required|number',
        's_grid.min_markup' => 'required|number',
        's_grid.n_close_orders' => 'required|number',
        's_grid.rentry_pprice_dist' => 'required|number',
        's_grid.rentry_pprice_dist_wallet_exposure_weighting' => 'required|number',
        's_grid.wallet_exposure_limit' => 'required|number',
    ];


    protected $validationAttributes = [
        'grid_json' => 'Grid configuration'
    ];

    public function mount()
    {
        $grid = json_decode($this->grid->grid_json,true);
        $this->l_grid = $grid['long'];
        $this->s_grid = $grid['short'];
    }

    protected function renderData()
    {
        return [
            'grid_modes' => config('antbot.grid_modes')
        ];
    }

    public function render()
    {
        return view('livewire.configs.grid-edit')->layoutData([
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
