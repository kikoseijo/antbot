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
            $table->decimal('size', 15, 8)->nullable();
            $table->decimal('position_value', 15, 8)->nullable();
            $table->decimal('entry_price', 15, 8)->nullable();
            $table->decimal('liq_price', 15, 8)->nullable();
            $table->decimal('bust_price', 15, 8)->nullable();
            $table->decimal('position_margin', 15, 8)->nullable();
            $table->decimal('occ_closing_fee', 15, 8)->nullable();
            $table->decimal('realised_pnl', 15, 8)->nullable();
            $table->decimal('unrealised_pnl', 15, 8)->nullable();
            $table->decimal('cum_realised_pnl', 15, 8)->nullable();
            $table->string('tp_sl_mode', 10)->nullable();
            $table->integer('risk_id')->nullable();
            $table->decimal('stop_loss', 15, 8)->nullable();
            $table->decimal('take_profit', 15, 8)->nullable();
            $table->decimal('trailing_stop', 15, 8)->nullable();
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
