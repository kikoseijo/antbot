<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('exchange_id')->index();
            $table->string('symbol', 30);
            $table->decimal('equity', 15, 8)->nullable();
            $table->decimal('available_balance', 15, 8)->nullable();
            $table->decimal('used_margin', 15, 8)->nullable();
            $table->decimal('order_margin', 15, 8)->nullable();
            $table->decimal('position_margin', 15, 8)->nullable();
            $table->decimal('occ_closing_fee', 15, 8)->nullable();
            $table->decimal('occ_funding_fee', 15, 8)->nullable();
            $table->decimal('wallet_balance', 15, 8)->nullable();
            $table->decimal('realised_pnl', 15, 8)->nullable();
            $table->decimal('unrealised_pnl', 15, 8)->nullable();
            $table->decimal('cum_realised_pnl', 15, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');
    }
};
