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
            $table->renameColumn('symbol', 'name');
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->unsignedInteger('symbol_id')->index()->after('name');
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
            $table->dropColumn('symbol_id');
            $table->renameColumn('bot_name', 'symbol');
        });
    }
};
