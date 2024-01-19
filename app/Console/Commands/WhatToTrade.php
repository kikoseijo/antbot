<?php

namespace App\Console\Commands;

use App\Models\Symbol;
use App\Models\Config;
use Illuminate\Console\Command;

class WhatToTrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-whattotrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync what to trade records.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->populateData();
        $path = storage_path() .'/whattotrade/quantdata.json';
        $records = json_decode(file_get_contents($path), true);
        foreach ($records as $record) {
            $symbol_name = \Arr::get($record, 'symbol');
            Symbol::where('exchange', 'bybit')
                ->where('name', $symbol_name)
                ->where('market', 'Futures')
                ->update(\Arr::except($record, 'symbol'));
        }

        return Command::SUCCESS;
    }

    protected function populateData()
    {
        $python_path = Config::find(1)->python_path;
        $commands = [ //
            'cd /home/antbot/antbot/bots/directional-scalper && '.$python_path.' api_scraper.py',
        ];

        foreach ($commands as $cmd) {
            exec($cmd, $output);
            $this->comment( implode( PHP_EOL, $output ) );
        }
    }


}
