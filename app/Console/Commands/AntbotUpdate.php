<?php

namespace App\Console\Commands;

use App\Models\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AntbotUpdate extends Command
{
    protected $signature = 'antbot:update';
    protected $description = 'Update application to the latest version.';

    public function handle()
    {
        if (Schema::hasTable('configs')) {
            $config = Config::find(1);
            $branch = $config->antbot_branch;
        } else {
            $branch = 'origin/master';
        }
        $commands = [
            'git fetch --all',
            "git reset --hard {$branch}",
            "git pull",
            "git submodule update --init --recursive",
            'composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs',
        ];

        Artisan::call('down', ['--render' => "errors::503", '--refresh' => 10]);

        foreach ($commands as $cmd) {
            $this->executeCommand($cmd);
        }

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        Artisan::call('view:cache');
        Artisan::call('up');


        return Command::SUCCESS;
    }

    protected function executeCommand(string $cmd)
    {
        exec($cmd, $output);
        $this->comment( implode( PHP_EOL, $output ) );
    }
}
