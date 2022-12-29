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
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->string('exchange', 12)->index();
            $table->string('name', 30);
            $table->string('status', 15);
            $table->string('base_currency', 10)->nullable();
            $table->string('quote_currency', 10)->nullable();
            $table->unsignedTinyInteger('price_scale')->nullable();
            $table->string('taker_fee', 30)->nullable();
            $table->string('maker_fee', 30)->nullable();
            $table->unsignedInteger('funding_interval')->nullable();
            $table->unsignedTinyInteger('min_leverage')->nullable();
            $table->unsignedTinyInteger('max_leverage')->nullable();
            $table->string('leverage_step', 30)->nullable();
            $table->string('min_price', 30)->nullable();
            $table->string('max_price', 30)->nullable();
            $table->string('tick_size', 30)->nullable();
            $table->unsignedInteger('max_trading_qty')->nullable();
            $table->unsignedInteger('min_trading_qty')->nullable();
            $table->unsignedInteger('qty_step')->nullable();
            $table->string('post_only_max_trading_qty', 30)->nullable();
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
        Schema::dropIfExists('symbols');
    }
};
