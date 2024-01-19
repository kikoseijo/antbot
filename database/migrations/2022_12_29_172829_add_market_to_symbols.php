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
        Schema::table('symbols', function (Blueprint $table) {
            $table->string('market', 10)->nullable()->after('name');
            $table->string('last_price', 20)->nullable()->after('market');
            $table->string('prev_price_24h', 30)->nullable()->after('last_price');
            $table->string('price_24h_pcnt', 20)->nullable()->after('prev_price_24h');
            $table->string('high_price_24h', 20)->nullable()->after('price_24h_pcnt');
            $table->string('low_price_24h', 20)->nullable()->after('high_price_24h');
            $table->string('prev_price_1h', 20)->nullable()->after('low_price_24h');
            $table->string('mark_price', 20)->nullable()->after('prev_price_1h');
            $table->string('index_price', 20)->nullable()->after('mark_price');
            $table->string('turnover_24h', 30)->nullable()->after('index_price');
            $table->string('volume_24h', 30)->nullable()->after('turnover_24h');
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
                'market', 'last_price', 'prev_price_24h', 'price_24h_pcnt',
                'high_price_24h', 'low_price_24h', 'prev_price_1h',
                'mark_price', 'index_price', 'turnover_24h', 'volume_24h'
            ]);
        });
    }
};
