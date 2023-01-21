<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AntbotUpdate extends Command
{
    protected $signature = 'antbot:update';
    protected $description = 'Update Antbot to the latest version.';

    public function handle()
    {
        $commands = [
            'php artisan down --render="errors::503" --refresh=10',
            'git fetch --all',
            'git reset --hard origin/master',
            'git pull origin master',
            'composer install --no-dev --prefer-dist --optimize-autoloader --ignore-platform-reqs',
            'php artisan migrate --force',
            'php artisan config:clear',
            'php artisan config:cache',
            'php artisan view:clear',
            'php artisan view:cache',
            'php artisan up',
        ];

        foreach ($commands as $cmd) {
            $this->executeCommand($cmd);
        }

        return Command::SUCCESS;
    }

    protected function executeCommand(string $cmd)
    {
        exec($cmd, $output);
        $this->comment( implode( PHP_EOL, $output ) );
    }
}
