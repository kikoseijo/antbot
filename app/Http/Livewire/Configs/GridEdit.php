<?php

namespace App\Http\Livewire\Configs;

use App\Models\Grid;
use Livewire\Component;
use Illuminate\Validation\Rule;

class GridEdit extends Component
{
    public Grid $grid;
    public array $common;
    public array $l_grid;
    public array $s_grid;
    public $title = 'Grid visual editor';

    protected $rules = [
        'grid.name' => 'required|string|max:12',
        'grid.description' => 'sometimes|string',
        'common.config_name' => 'required|string',
        'common.logging_level' => 'required|integer',
    ];

    protected $validationAttributes = [
        'grid_json' => 'Grid configuration'
    ];

    public function mount()
    {
        $common_rules = [
            'auto_unstuck_ema_dist' => 'required|float',
            'auto_unstuck_wallet_exposure_threshold' => 'required|float',
            'backwards_tp' => 'required|boolean',
            'ema_span_0' => 'required|float',
            'ema_span_1' => 'required|float',
            'initial_eprice_ema_dist' => 'required|float',
            'initial_qty_pct' => 'required|float',
            'markup_range' => 'required|float',
            'min_markup' => 'required|float',
            'n_close_orders' => 'required|numeric',
            'wallet_exposure_limit' => 'required|float',
        ];
        $recursive_rules = [
            'rentry_pprice_dist' => 'required|float',
            'rentry_pprice_dist_wallet_exposure_weighting' => 'required|float',
            'ddown_factor' => 'required|float',
        ];
        $static_rules = [
            'eprice_exp_base' => 'required|float',
            'eprice_pprice_diff' => 'required|float',
            'grid_span' => 'required|float',
            'secondary_allocation' => 'required|float',
            'secondary_pprice_diff' => 'required|float',
        ];
        $neat_rules = [
            'eqty_exp_base' => 'required|float',
        ];

        if ($this->grid->type == 'recursive') {
            $this->rules['l_grid'] = array_merge($common_rules, $recursive_rules);
            $this->rules['s_grid'] = array_merge($common_rules, $recursive_rules);
        } else if ($this->grid->type == 'static') {
            $this->rules['l_grid'] = array_merge($common_rules, $static_rules);
            $this->rules['s_grid'] = array_merge($common_rules, $static_rules);
        } else {
            $this->rules['l_grid'] = array_merge($common_rules, $neat_rules);
            $this->rules['s_grid'] = array_merge($common_rules, $neat_rules);
        }

        $grid = $this->grid->grid;
        $this->common['config_name'] = $grid['config_name'];
        $this->common['logging_level'] = $grid['logging_level'];
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
        $this->validate([
            'grid.name' => [
                Rule::unique('grids', 'name')
                    ->ignore($this->grid->id)
                    ->ignore(auth()->user()->id)
            ],
        ]);
        $this->grid->grid_json = json_encode([
            'config_name' => \Str::snake($this->common['config_name']),
            'logging_level' => $this->common['logging_level'],
            'long' => array_merge($this->l_grid, ['enabled' => 'false']),
            'short' => array_merge($this->s_grid, ['enabled' => 'false']),
        ], JSON_PRETTY_PRINT);

        $this->grid->save();

        $this->grid->saveConfigToDisk();

        if (auth()->user()->isAdmin()) {
            session()->flash('message', 'File saved into: ' . $this->grid->file_path . '.');
        }

        session()->flash('status', 'grid-updated');
    }
}
