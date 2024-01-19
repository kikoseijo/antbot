<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PositionsTable extends DataTableComponent
{
    protected $model = Position::class;

    // public function render()
    // {
    //     if ($this->exchange->user_id != auth()->user()->id) {
    //         return abort(403, 'Unauthorized');
    //     }
    //
    //     $this->setFilter('exchange_id', $this->exchange->id);
    //     parent::render();
    // }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('symbol', 'asc')
            // ->setRefreshTime(10000)
            ->setTheadAttributes([
                'class' => config('antbot.css.thead'),
            ])->setConfigurableAreas([
                // 'toolbar-left-start' => 'path.to.my.view',
                // 'toolbar-left-end' => 'path.to.my.view',
                // 'toolbar-left-end' => ['exchanges.partials.select-exchange', [
                //     'exchange' => $this->exchange,
                // ]],
                // 'toolbar-right-start' => 'exchanges.partials.traded-records-btn',
                // 'toolbar-right-end' => 'path.to.my.view',
                // 'before-toolbar' => 'path.to.my.view',
                // 'after-toolbar' => 'path.to.my.view',
                // 'before-pagination' => 'path.to.my.view',
                // 'after-pagination' => 'path.to.my.view',
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
                        'class' => 'whitespace-nowrap text-sm font-bold px-1 py-2 '.$ex_css.' no-underline hover:underline',
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
                if (in_array($column->getField(),['size', 'position_value', 'entry_price', 'liq_price', 'bust_price', 'position_margin', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl', 'risk_id'])) {
                    return [
                        'default' => false,
                        'class' => 'text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400 px-1 py-3 text-right',
                    ];
                }
                return [
                    'default' => false,
                    'class' => 'text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400 px-1 py-3 text-center',
                ];
            })->setThSortButtonAttributes(function (Column $column) {
                if (in_array($column->getField(),['size', 'position_value', 'entry_price', 'liq_price', 'bust_price', 'position_margin', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl', 'risk_id'])) {
                    return [
                        'default' => false,
                        'class' => 'flex justify-center flex-row items-center space-x-1 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider group focus:outline-none dark:text-gray-400',
                    ];
                }
                return [
                    'default' => false,
                    'class' => 'flex justify-center flex-row items-center space-x-1 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider group focus:outline-none dark:text-gray-400',
                ];
            })->setFooterTdAttributes(function(Column $column, $rows) {
                if (in_array($column->getField(),['size', 'position_value', 'entry_price', 'liq_price', 'bust_price', 'position_margin', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl', 'risk_id'])) {
                    return [
                        'default' => false,
                        'class' => 'whitespace-nowrap text-sm font-medium dark:text-white dark:bg-gray-800 px-1 py-2 text-right',
                    ];
                }
                return [
                    'default' => false,
                    'class' => 'whitespace-nowrap text-sm font-medium dark:text-white dark:bg-gray-800 px-1 py-2',
                ];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("",'side')->sortable()->view('exchanges.partials.position-side'),
            Column::make("Symbol", "symbol")->sortable()->searchable()->view('exchanges.partials.position-symbol'),
            Column::make("", "leverage")->sortable()->view('exchanges.partials.position-leverage'),
            Column::make("Size", "size")->sortable()->format(
                fn($value, $row, Column $column) => number($value, 0)
            ),
            Column::make("Value", "position_value")->sortable()->format(
                fn($value, $row, Column $column) => number($value)
            )->footer(fn($rows) => view('exchanges.partials.position-totalprice')->withValue($rows->sum('position_value'))),
            Column::make("Entry", "entry_price")->sortable()->format(
                fn($value, $row, Column $column) => number($value, $row->coin->price_scale)
            ),
            Column::make("Mark")
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
            // Column::make("Margin", "position_margin")->sortable()->format(
            //     fn($value, $row, Column $column) => number($value)
            // ),
            Column::make("Ord.")
                ->label(
                    fn($row, Column $column) => view('exchanges.partials.position-orders-count')->withRow($row)
            )->footer(fn($rows) => view('exchanges.partials.position-totalorders-count')->withRows($rows)),
            Column::make("U. PNL", "unrealised_pnl")
                ->sortable()
                ->view('exchanges.partials.position-pnl')
                ->footer(fn($rows) => view('exchanges.partials.position-totalprice')->withValue($rows->sum('unrealised_pnl'))),
            Column::make("R. PNL", "realised_pnl")
                ->sortable()
                ->view('exchanges.partials.position-pnl')
                ->footer(fn($rows) => view('exchanges.partials.position-totalprice')->withValue($rows->sum('realised_pnl'))),
            Column::make("A. PNL", "cum_realised_pnl")
                ->sortable()
                ->view('exchanges.partials.position-pnl')
                ->footer(fn($rows) => view('exchanges.partials.position-totalprice')->withValue($rows->sum('cum_realised_pnl'))),
            // Column::make("RiskID", "risk_id")->sortable(),
            Column::make("WE", "exchange.usdt_balance")->sortable()->view('exchanges.partials.position-wallet-exposure'),

            // Column::make("Created at", "created_at")->sortable(),
            // Column::make("Updated at", "updated_at")->sortable(),
        ];
    }

    // public function filters(): array
    // {
    //     $exchanges = auth()->user()->exchanges()->orderBy('name')->get()->pluck('name', 'id');
    //     return [
    //         SelectFilter::make('Exchange', 'exchange_id')
    //             ->options($exchanges->toArray())
    //             ->filter(function(Builder $builder, string $value) {
    //                 $builder->where('exchange_id', $value);
    //             }),
    //     ];
    // }

    public function builder(): Builder
    {
        return Position::query()
            ->whereExchangeId(auth()->user()->exchange_id)
            // ->when(true, fn($query, $active) => $query->where('exchange_id', $this->exchange->id))
            ->with('exchange', 'coin', 'orders', 'buy_orders', 'sell_orders')
            ->withCount('orders')
            ->select('positions.id', 'exchange_id', 'symbol');
    }
}
