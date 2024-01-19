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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 50)->index();
            $table->unsignedInteger('position_id')->index();
            $table->string('side', 5); // Buy, Sell
            $table->string('order_type', 10); // Limit, Market
            $table->string('price', 30)->nullable();
            $table->unsignedInteger('qty')->nullable();
            $table->string('time_in_force', 20)->nullable(); // GoodTillCancel, ImmediateOrCancel, FillOrKill, PostOnly
            // Created, New, Rejected, PartiallyFilled, Filled, PendingCancel, Cancelled
            // Only for conditional orders:
            // Untriggered , Deactivated, Triggered, Active
            $table->string('order_status', 20)->nullable();
            $table->string('last_exec_price', 30)->nullable();
            $table->string('cum_exec_qty', 30)->nullable();
            $table->string('cum_exec_value', 30)->nullable();
            $table->string('cum_exec_fee', 30)->nullable();
            $table->boolean('reduce_only'); //true means close order, false means open position
            $table->timestamp('created_time')->nullable();
            $table->timestamp('updated_time')->nullable();
            $table->string('take_profit', 30)->nullable();
            $table->string('stop_loss', 30)->nullable();
            $table->string('tp_trigger_by', 15)->nullable(); // LastPrice, IndexPrice, MarkPrice, UNKNOWN
            $table->string('sl_trigger_by', 15)->nullable(); // LastPrice, IndexPrice, MarkPrice, UNKNOWN
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
        Schema::dropIfExists('orders');
    }
};
