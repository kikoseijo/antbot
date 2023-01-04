<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // cd /home/antbot/antbot && /opt/remi/php81/root/usr/bin/php artisan sche
        // https://laravel.com/docs/9.x/scheduling#schedule-frequency-options
        $schedule->command('antbot:sync-symbols')->everyTwoMinutes();
        $schedule->command('antbot:sync-orders')->everyThreeMinutes();
        $schedule->command('antbot:keep-alive')->everyFiveMinutes();
        $schedule->command('antbot:sync-balance')->everyTenMinutes();
        $schedule->command('antbot:sync-positions')->everyTwoMinutes();
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
