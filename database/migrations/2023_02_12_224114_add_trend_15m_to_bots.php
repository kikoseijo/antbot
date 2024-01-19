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
        Schema::table('bots', function (Blueprint $table) {
            $table->boolean('is_on_trend')->default(0)->after('show_logs');
            $table->string('trend_5m', 1)->default('l')->after('is_on_trend');
            $table->string('trend_15m', 1)->default('l')->after('trend_5m');
            $table->string('trend_30m', 1)->default('l')->after('trend_15m');
            $table->string('trend_1h', 1)->default('l')->after('trend_30m');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn(['is_on_trend', 'trend_5m', 'trend_15m', 'trend_30m', 'trend_1h']);
        });
    }
};
