<?php

namespace App\Services;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class LaravelPython
{
    public function run(string $filename, array $parameters = [], $log_file)
    {
        $args = [
            'nohup', '/usr/local/bin/python3.8', $filename,
        ];
        $args = array_merge($args, $parameters);
        $args = array_merge($args, ['>', $log_file, '2>&1', '&']);
        $process = new Process($args);
        $process->setWorkingDirectory('/home/antbot/passivbot');
        // $process->setOptions(['create_new_console' => true]);
        // $process->disableOutput();
        // $process->start();
        $process->run();
        $process->wait();

        if ($process->isSuccessful()) {
            return $process->getPid();
        }

        return 0;
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
