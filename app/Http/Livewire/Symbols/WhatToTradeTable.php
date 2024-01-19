<?php

namespace App\Http\Livewire\Symbols;

use App\Models\Symbol;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WhatToTradeTable extends DataTableComponent
{
    protected $model = Symbol::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setRefreshTime(10000)
            ->setDefaultSort('vol_1m', 'desc')
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
                        'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-1 text-right',
                    ];
                };
                if ($column->getField() == 'updated_at') {
                    return [
                        'default' => false,
                        'class' => 'whitespace-nowrap text-xs font-medium dark:text-white px-1 py-1 text-right',
                    ];
                };
                return [
                    'default' => false,
                    'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-1',
                ];
            })->setThAttributes(function (Column $column) {
                return [
                    'default' => false,
                    'class' => 'text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-900 dark:text-gray-400 px-1 py-3 text-center',
                ];
            });

    }

    // $table->integer('trend_perc')->nullable()->after('ma6_5m_low');
    // $table->string('trend', 10)->nullable()->after('trend_perc');
    // $table->integer('funding')->nullable()->after('trend');

    public function columns(): array
    {
        return [
            Column::make("Symbol", 'name')->sortable()->searchable()->format(function ($value, $row, Column $column)
            {
                $res = '';
                if ($row->bots->count() > 0) {
                    $res .= '<span class="text-yellow-300">' . $value .'</span>';
                } else {
                    $res = $value;
                }
                return  $res;
            })->html(),
            Column::make("Price", 'last_price')->sortable(),
            Column::make("1m V.", 'vol_1m')->sortable(),
            Column::make("Spread", 'spread_1m')->sortable(),
            Column::make("5m V.", 'vol_5m')->sortable(),
            Column::make("Spread", 'spread_5m')->sortable(),
            Column::make("5MA High", 'ma6_5m_high')->sortable()->format(function ($value, $row, Column $column)
            {
                return  number($value, 3);
            }),
            Column::make("5MA Low", 'ma6_5m_low')->sortable()->format(function ($value, $row, Column $column)
            {
                return  number($value, 3);
            }),
            Column::make("15m Spread", 'spread_15m')->sortable(),
            Column::make("30m V.", 'vol_30m')->sortable(),
            Column::make("Spread", 'spread_30m')->sortable(),
            Column::make("Trend", 'trend')->sortable()->format(function ($value, $row, Column $column)
            {
                $color = $value == 'long' ? 'green' : ($value == 'short' ? 'red' : 'yellow');
                return '<span class="text-'. $color .'-500 dark:text-'. $color .'-500">'.ucfirst($value).'</span>';
            })->html(),
            Column::make("Trend %", 'trend_perc')->sortable(),
            Column::make("Funding", 'funding')->sortable(),
            Column::make("Updated at", "updated_at")->sortable()->format(
                fn($value, $row, Column $column) => $value->format('d/m/y H:i')
            ),
        ];
    }

    public function builder(): Builder
    {
        return Symbol::query()
            ->where('vol_1m', '>', '0')
            ->select('id', 'name');
    }
}
