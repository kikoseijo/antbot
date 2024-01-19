<?php

use App\Models\User;
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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->index();
            $table->string('name');
            $table->morphs('historicable');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->mediumText('original')->nullable();
            $table->mediumText('changes')->nullable();
            $table->string('status', 25)->default('running');
            $table->text('exception');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
};
