<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class PositionsTable extends DataTableComponent
{
    protected $model = Position::class;
    public Exchange $exchange;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('symbol', 'asc')
            // ->setRefreshTime(10000)
            ->setTheadAttributes([
                'class' => config('antbot.css.thead'),
            ])
            ->setTbodyAttributes([
                'class' => config('antbot.css.tbody'),
            ])->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
                if (in_array($column->getField(),['size', 'position_value', 'entry_price', 'liq_price', 'bust_price', 'position_margin', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl', 'risk_id'])) {
                    return [
                        'class' => 'px-3 py-3 text-right',
                    ];
                }
                return [
                    'default' => true,
                    'class' => 'px-3 py-3',
                ];
            })->setThAttributes(function (Column $column) {
                return [
                    'default' => true,
                    'class' => 'px-3 py-3 text-center',
                ];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("",'side')->sortable()->format(function($value, $row, Column $column){
                $color = $value == 'Buy' ? 'green' : 'red';
                return '<div class="h-2.5 w-2.5 rounded-full bg-'.$color.'-500 mr-2 mt-1"></div>';
            })->html(),
            Column::make("Symbol", "symbol")->sortable()->searchable()->format(
                function($value, $row, Column $column){
                    $color = $row->side == 'Buy' ? 'green' : 'red';
                    $res = '<a class"font-bold underline hover:no-underline" href="' . $row->exchange_link . '" alt="Exchange trade view" target="_blank">';
                    $res .=  $value;
                    $res .= '</a>';

                    return $res;
                }
            )->html(),

            Column::make("Size", "size")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Value", "position_value")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Entry", "entry_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value, 4)
            ),
            Column::make("Liq.", "liq_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Bust.", "bust_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Margin", "position_margin")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Reaized PNL", "realised_pnl")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Unrealized PNL", "unrealised_pnl")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Acc. PNL", "cum_realised_pnl")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("RiskID", "risk_id")->sortable(),
            Column::make("", "leverage")->sortable()->format(
                function($value, $row, Column $column) {
                    $res = '<span class="bg-yellow-100 text-yellow-800 text-xs font-semibold ml-2 px-0.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900">';
                    $res .= "x{$value}</span>";
                    return $res;
                }
            )
            ->html(),

            // Column::make("Created at", "created_at")->sortable(),
            // Column::make("Updated at", "updated_at")->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return Position::query()
            ->whereExchangeId($this->exchange->id)
            ->with('exchange')
            ->select('exchange_id', 'symbol');
    }
}
