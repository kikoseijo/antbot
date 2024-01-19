<?php

use App\Models\Bot;
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
        Schema::table('routines', function (Blueprint $table) {
            $table->foreignIdFor(Bot::class, 'bot_group_id')
                ->nullable()
                ->index()
                ->after('user_id');
            $table->timestamp('end_scheduled_at')->nullable();
            $table->boolean('is_end_scheduled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routines', function (Blueprint $table) {
            $table->dropColumn(['bot_group_id', 'end_scheduled_at']);
        });
    }
};
