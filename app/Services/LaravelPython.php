<?php

namespace App\Services;


use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class LaravelPython
{
    public function run(string $filename, array $parameters = [])
    {
        $process = new Process('python3.8', $filename], $parameters));
        $process->run();
        $process->wait();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return rtrim($process->getOutput());
    }
}
