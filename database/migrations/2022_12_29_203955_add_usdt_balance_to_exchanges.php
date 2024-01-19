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
            $table->string('usd_balance', 30)->nullable()->after('name');
            $table->string('usdt_balance', 30)->nullable()->after('usd_balance');
            $table->string('btc_balance', 30)->nullable()->after('usdt_balance');
            $table->string('eth_balance', 30)->nullable()->after('btc_balance');
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
