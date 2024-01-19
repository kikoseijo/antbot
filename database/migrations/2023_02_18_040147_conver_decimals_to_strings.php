<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $jobs = [
        'trades' => ['closed_size', 'cum_entry_value', 'avg_entry_price', 'cum_exit_value', 'avg_exit_price', 'closed_pnl'],
        'exchanges' => ['usd_balance', 'usdt_balance', 'btc_balance', 'eth_balance'],
        'symbols' => ['index_price', 'turnover_24h', 'volume_24h'],
        'orders' => ['price', 'last_exec_price', 'cum_exec_qty', 'cum_exec_value', 'cum_exec_fee', 'take_profit', 'stop_loss'],
        'balances' => [
            'equity', 'available_balance', 'used_margin', 'order_margin', 'position_margin', 'occ_closing_fee',
            'occ_funding_fee', 'wallet_balance', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl'
        ],
        'positions' => [
            'size', 'position_value', 'entry_price', 'liq_price', 'bust_price', 'position_margin',
            'occ_closing_fee', 'realised_pnl', 'unrealised_pnl', 'cum_realised_pnl', 'stop_loss',
            'take_profit', 'trailing_stop'
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->jobs as $key => $value) {
            Schema::table($key, function (Blueprint $table) use ($value) {
                foreach ($value as $column) {
                    $table->string($column, 30)->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->jobs as $key => $value) {
            Schema::table($key, function (Blueprint $table) use ($value) {
                foreach ($value as $column) {
                    $table->decimal($column, 30, 8)->nullable()->change();
                }
            });
        }
    }
};
