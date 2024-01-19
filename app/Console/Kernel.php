<?php

namespace App\Console;

use App\Models\Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Schema;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // * * * * * cd /home/antbot/antbot && php artisan schedule:run >> /dev/null 2>&1
        // https://laravel.com/docs/9.x/scheduling#schedule-frequency-options
        $schedule->command('antbot:sync-symbols')->everyTwoMinutes()->withoutOverlapping(); // 1
        $schedule->command('antbot:sync-positions')->everyFiveMinutes()->withoutOverlapping(); // 2
        $schedule->command('antbot:sync-orders')->everyFiveMinutes()->withoutOverlapping(); // 3
        $schedule->command('antbot:sync-balance')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('antbot:sync-trades')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('antbot:keep-alive')->everyFiveMinutes()->withoutOverlapping();
        // $schedule->command('antbot:follow-trend')->everyTenMinutes();

        $settings = Schema::hasTable('configs') ? Config::find(1) : new Config;
        if ($settings->enable_what4trade) {
            $schedule->command('antbot:sync-whattotrade')->everyFourMinutes()->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
