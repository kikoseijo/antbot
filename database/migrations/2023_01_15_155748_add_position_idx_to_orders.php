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
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('ref_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('ref_id', 'position_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->unsignedInteger('ref_id')->nullable()->after('exchange_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('position_idx', 'ref_id');
        });
    }
};
