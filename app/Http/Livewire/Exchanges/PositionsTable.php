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
                        'default' => false,
                        'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-2 text-right',
                    ];
                }
                if($column->isField('symbol')){
                    $ex_css = $row->exchange->hasRunningBotsForSymbol($row->symbol) ? 'text-yellow-300 decoration-yellow-300' : '';
                    return [
                        'default' => false,
                        'class' => 'whitespace-nowrap text-sm font-bold px-1 py-2 '.$ex_css.' underline hover:no-underline',
                    ];
                }
                if($column->isField('side')){
                    return [
                        'default' => false,
                        'class' => 'whitespace-nowrap text-center px-1 py-2',
                    ];
                }
                return [
                    'default' => false,
                    'class' => 'whitespace-nowrap text-sm font-medium dark:text-white px-1 py-2',
                ];
            })->setThAttributes(function (Column $column) {
                return [
                    'default' => false,
                    'class' => 'text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400 px-1 py-2 text-center',
                ];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("",'side')->sortable()->format(function($value, $row, Column $column){
                $color = $value == 'Buy' ? 'green' : 'red';
                return '<div class="h-2.5 w-2.5 rounded-full bg-'.$color.'-500 ml-2 mt-0.5"></div>';
            })->html(),
            Column::make("Symbol", "symbol")->sortable()->searchable()->format(
                function($value, $row, Column $column){
                    $res = '<a class"font-bold " href="' . $row->exchange_link . '" alt="Exchange trade view" target="_blank">';
                    $res .=  $value;
                    $res .= '</a>';

                    return $res;
                }
            )->html(),

            Column::make("Size", "size")->sortable()->format(
                fn($value, $row, Column $column) => number($value, 0)
            ),
            Column::make("Value", "position_value")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Entry", "entry_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value, $row->coin->price_scale)
            ),
            Column::make("L. price")
                ->label(
                    fn($row, Column $column) => view('exchanges.partials.position-price')->withRow($row)
            ),
            Column::make("%")
                ->label(
                    fn($row, Column $column) => view('exchanges.partials.position-distance')->withRow($row)
            ),
            Column::make("Liq.", "liq_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            // Column::make("Bust.", "bust_price")->sortable()->format(
            //     fn($value, $row, Column $column) => number($value)
            // ),
            Column::make("Margin", "position_margin")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            ),
            Column::make("Ord.")
                ->label(
                    fn($row, Column $column) => view('exchanges.partials.position-orders-count')->withRow($row)
            ),
            Column::make("U. PNL", "unrealised_pnl")->sortable()->view('exchanges.partials.position-pnl'),
            Column::make("R. PNL", "realised_pnl")->sortable()->view('exchanges.partials.position-pnl'),
            Column::make("A. PNL", "cum_realised_pnl")->sortable()->view('exchanges.partials.position-pnl'),
            // Column::make("RiskID", "risk_id")->sortable(),
            Column::make("WE", "exchange.usdt_balance")->sortable()->view('exchanges.partials.position-wallet-exposure'),
            // Column::make("WE")
            // ->label(
            //     fn($row, Column $column) => view('exchanges.partials.position-wallet-exposure')->withRow($row)
            // ),
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
            ->with('exchange', 'coin', 'orders')
            ->select('positions.id', 'exchange_id', 'symbol');
    }
}
