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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            // General Bot settings
            $table->string('symbol');
            $table->string('market_type', 12)->default('futures');
            $table->string('grid_mode', 12)->default('recursive');
            $table->unsignedInteger('grid_id')->nullable();
            $table->unsignedInteger('exchange_id')->default(0);
            $table->unsignedFloat('assigned_balance', 50, 5)->default(0);
            // Long mode settings
            $table->string('lm', 2)->default('n');
            $table->unsignedFloat('lwe', 4, 2)->default(0.20);
            // Short mode settings
            $table->string('sm', 2)->default('n');
            $table->unsignedFloat('swe', 4, 2)->default(0.15);
            // Runtime
            $table->unsignedInteger('pid')->nullable()->unique();
            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
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
        Schema::dropIfExists('bots');
    }
};
