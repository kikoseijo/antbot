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
        Schema::table('exchanges', function (Blueprint $table) {
            $table->decimal('usd_balance', 10, 2)->nullable()->after('name');
            $table->decimal('usdt_balance', 10, 2)->nullable()->after('usd_balance');
            $table->decimal('btc_balance', 10, 2)->nullable()->after('usdt_balance');
            $table->decimal('eth_balance', 10, 2)->nullable()->after('btc_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn(['usdt_balance', 'usd_balance', 'btc_balance', 'eth_balance']);
        });
    }
};
