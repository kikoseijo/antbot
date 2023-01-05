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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('exchange_id')->index();
            $table->unsignedInteger('position_id')->index();
            $table->string('symbol', 30);
            $table->string('order_id', 64);
            $table->string('side', 5);
            $table->decimal('qty', 15, 8)->nullable();
            $table->unsignedInteger('order_price')->nullable();
            $table->string('order_type', 10);
            $table->string('exec_type', 10);
            $table->decimal('closed_size', 15, 8)->nullable();
            $table->decimal('cum_entry_value', 15, 8)->nullable();
            $table->decimal('avg_entry_price', 15, 8)->nullable();
            $table->decimal('cum_exit_value', 15, 8)->nullable();
            $table->decimal('avg_exit_price', 15, 8)->nullable();
            $table->decimal('closed_pnl', 15, 8)->nullable();
            $table->unsignedInteger('fill_count')->nullable();
            $table->unsignedTinyInteger('leverage')->nullable();
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
        Schema::dropIfExists('trades');
    }
};
