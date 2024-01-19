<?php

namespace App\Console\Commands;

use App\Routines\ExecuteBotRoutine;
use Illuminate\Console\Command;

class FollowTrend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:follow-trend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enables trend follow for bots.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routines = new ExecuteBotRoutine;
        $routines->run('follow_30m_trend');

        return Command::SUCCESS;
    }
}
