<?php

namespace App\Http\Livewire\Symbols;

use App\Models\Symbol;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SymbolsDataTable extends DataTableComponent
{
    protected $model = Symbol::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setRefreshTime(10000)
            ->setDefaultSort('name', 'asc')
            ->setTheadAttributes([
                'class' => config('antbot.css.thead'),
            ])
            ->setTbodyAttributes([
                'class' => config('antbot.css.tbody')
            ])->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
                if (in_array($column->getField(),[
                    'last_price', 'mark_price', 'index_price', 'turnover_24h', 'volume_24h', 'min_leverage',
                    'max_leverage', 'min_trading_qty'
                    ])) {
                    return [
                        'default' => false,
                        'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-2 text-right',
                    ];
                };
                return [
                    'default' => false,
                    'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-2',
                ];
            })->setThAttributes(function (Column $column) {
                return [
                    'default' => false,
                    'class' => 'text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400 px-1 py-3 text-center',
                ];
            });

    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")->sortable(),
            Column::make("Exchange", "exchange")->sortable(),
            Column::make("Name")->sortable()->searchable()->format(function ($value, $row, Column $column)
            {
                $res = '';
                if ($row->bots->count() > 0) {
                    $res .= '<span class="text-yellow-300">' . $value .'</span>';
                } else {
                    $res = $value;
                }
                return  $res;
            })->html(),
            Column::make("Market")->sortable(),
            Column::make("Last Price")->sortable(),
            Column::make("Mark Price")->sortable(),
            Column::make("Index Price")->sortable(),
            Column::make("24h Turnover", 'turnover_24h')->sortable()->format(
                fn($value, $row, Column $column) => '$' . bignumber($value)
            ),
            Column::make("24h Volume", 'volume_24h')->sortable()->format(
                fn($value, $row, Column $column) => '$' . bignumber($value)
            ),
            Column::make("Status")->sortable(),
            Column::make("Min.L", 'min_leverage')->sortable(),
            Column::make("Max.L", 'max_leverage')->sortable(),
            Column::make("Min trading qty")->sortable(),
            Column::make("Updated at", "updated_at")->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return Symbol::query()
            ->with('bots')
            ->select('id', 'name');
    }
}
