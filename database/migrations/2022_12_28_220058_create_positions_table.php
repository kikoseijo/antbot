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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('exchange_id')->index();
            $table->string('symbol', 30);
            $table->string('side', 10);
            $table->string('mode', 20);
            $table->unsignedTinyInteger('leverage')->nullable();
            $table->unsignedTinyInteger('auto_add_margin')->nullable();
            $table->unsignedTinyInteger('deleverage_indicator')->nullable();
            $table->unsignedTinyInteger('position_idx')->nullable();
            $table->boolean('is_isolated');
            $table->string('size', 30)->nullable();
            $table->string('position_value', 30)->nullable();
            $table->string('entry_price', 30)->nullable();
            $table->string('liq_price', 30)->nullable();
            $table->string('bust_price', 30)->nullable();
            $table->string('position_margin', 30)->nullable();
            $table->string('occ_closing_fee', 30)->nullable();
            $table->string('realised_pnl', 30)->nullable();
            $table->string('unrealised_pnl', 30)->nullable();
            $table->string('cum_realised_pnl', 30)->nullable();
            $table->string('tp_sl_mode', 10)->nullable();
            $table->integer('risk_id')->nullable();
            $table->string('stop_loss', 30)->nullable();
            $table->string('take_profit', 30)->nullable();
            $table->string('trailing_stop', 30)->nullable();
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
        Schema::dropIfExists('positions');
    }
};
