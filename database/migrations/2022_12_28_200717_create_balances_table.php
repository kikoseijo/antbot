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
            $table->string('equity', 30)->nullable();
            $table->string('available_balance', 30)->nullable();
            $table->string('used_margin', 30)->nullable();
            $table->string('order_margin', 30)->nullable();
            $table->string('position_margin', 30)->nullable();
            $table->string('occ_closing_fee', 30)->nullable();
            $table->string('occ_funding_fee', 30)->nullable();
            $table->string('wallet_balance', 30)->nullable();
            $table->string('realised_pnl', 30)->nullable();
            $table->string('unrealised_pnl', 30)->nullable();
            $table->string('cum_realised_pnl', 30)->nullable();
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
