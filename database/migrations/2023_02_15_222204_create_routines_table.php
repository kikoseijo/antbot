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
        Schema::create('routines', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('name', 52);
            $table->string('type', 20)->default('passivbot');
            $table->json('action');
            $table->timestamp('triggered_at')->nullable();
            $table->string('triggered_by')->nullable();
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
        Schema::dropIfExists('routines');
    }
};
