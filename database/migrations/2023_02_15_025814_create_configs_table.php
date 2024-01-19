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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable_grids')->default(1);
            $table->tinyInteger('exchange_max_bots')->default(10);
            $table->string('python_path')->default('python');
            $table->string('passivbot_path')->default('/home/antbot/passivbot');
            $table->string('passivbot_logs_path')->default('/home/antbot/logs');
            $table->string('passivbot_grid_neat')->default('configs/live/neat_grid_mode.example.json');
            $table->string('passivbot_grid_recursive')->default('configs/live/recursive_grid_mode.example.json');
            $table->string('passivbot_grid_clock')->default('configs/live/clock_mode.example.json');
            $table->string('passivbot_grid_static')->default('configs/live/static_grid_mode.example.json');
            $table->string('antbot_branch')->default('origin/master');
            $table->json('data')->nullable();
        });

        \App\Models\Config::create(); // Create base settings.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
};
