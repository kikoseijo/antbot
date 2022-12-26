<?php

namespace App\Services;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class LaravelPython
{
    public function run(string $filename, array $parameters = [], $log_file)
    {
        $bot_path = config('antbot.paths.bot_path');
        $params = implode(" ", $parameters);
        $args = [
            'nohup', '/usr/local/bin/python3.8', '-u', $filename, $params,
            '>', $log_file, '2>&1', '& echo $!; '
        ];
        $command = implode(" ", $args);
        // \Log::debug($command);
        chdir($bot_path);
        $pid = exec($command, $out);

        return $pid;
    }

    public function kill($pid): bool
    {
        $args = ['kill', $pid];
        $process = new Process($args);
        $process->run();
        $process->wait();

        return $process->isSuccessful();
    }
}

/*
/usr/local/bin/python3.8 /home/antbot/passivbot/passivbot.py bybit_01 VETUSDT configs/live/recursive.json -lm n -lw 0.01 -sm m -sw 0.15 > /home/antbot/klogs/2/VETUSDT.log
nohup /usr/local/bin/python3.8 /home/antbot/passivbot/passivbot.py bybit_01 VETUSDT configs/live/recursive.json -lm n -lw 0.01 -sm m -sw 0.15 > /home/antbot/klogs/2/VETUSDT.log &
cd /home/antbot/passivbot && /usr/local/bin/python3.8 /home/antbot/passivbot/passivbot.py bybit_01 VETUSDT configs/live/recursive.json -lm n -lw 0.01 -sm m -sw 0.15
*/
