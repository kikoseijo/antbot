<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('symbols', function (Blueprint $table) {
            $table->unsignedInteger('vol_1m')->nullable()->after('name');
            $table->unsignedInteger('vol_5m')->nullable()->after('vol_1m');
            $table->unsignedInteger('vol_30m')->nullable()->after('vol_5m');
            $table->string('spread_1m', 30)->nullable()->after('vol_30m');
            $table->string('spread_5m', 30)->nullable()->after('spread_1m');
            $table->string('spread_15m', 30)->nullable()->after('spread_5m');
            $table->string('spread_30m', 30)->nullable()->after('spread_15m');
            $table->string('ma6_5m_high', 30)->nullable()->after('spread_30m');
            $table->string('ma6_5m_low', 30)->nullable()->after('ma6_5m_high');
            $table->string('trend_perc', 30)->nullable()->after('ma6_5m_low');
            $table->string('trend', 10)->nullable()->after('trend_perc');
            $table->string('funding', 30)->nullable()->after('trend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symbols', function (Blueprint $table) {
            $table->dropColumn([
                'vol_1m', 'vol_5m', 'vol_30m', 'spread_1m', 'spread_5m',
                'spread_15m', 'spread_30m', 'ma6_5m_high', 'ma6_5m_low',
                'trend_perc', 'trend', 'funding'
            ]);
        });
    }
};
