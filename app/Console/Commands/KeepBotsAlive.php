<?php

namespace App\Console\Commands;

use App\Models\Bot;
use Illuminate\Console\Command;

class KeepBotsAlive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:keep-alive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will search for running bots and will boot them up in case of not running state.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = true;

        $bots = Bot::where('pid', '>', 0)->get();

        foreach ($bots as $bot)
        {
            if (!$bot->isRunning())
            {
                $bot->start($force);
            }
        }

        return Command::SUCCESS;
    }
}
