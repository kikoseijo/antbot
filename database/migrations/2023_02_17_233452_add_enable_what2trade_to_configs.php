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
        Schema::table('configs', function (Blueprint $table) {
            $table->boolean('enable_what2trade')->default(0)->after('enable_grids');
            $table->boolean('enable_bots')->default(1)->after('enable_what2trade');
            $table->boolean('enable_positions')->default(1)->after('enable_bots');
            $table->boolean('enable_routines')->default(1)->after('enable_positions');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('exchange_id')->default(0)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn(['enable_what2trade', 'enable_bots', 'enable_positions', 'enable_routines']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('exchange_id');
        });
    }
};
