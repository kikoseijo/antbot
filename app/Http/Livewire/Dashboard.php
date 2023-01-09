<?php

namespace App\Http\Livewire;

use App\Models\Exchange;
use Livewire\Component;

class Dashboard extends Component
{
    public $title = 'Dashboard';

    public function render()
    {
        $exchanges = Exchange::mine()
            ->orderBy('name', 'asc')
            ->with('bots')
            ->withCount('bots')
            ->get();

        $data = [
            'exchanges' => $exchanges
        ];

        return view('livewire.dashboard', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function showLogs(Exchange $exchange)
    {
        $logs_path = config('antbot.paths.logs_path');
        $logs_route =  "{$logs_path}/{$exchange->id}/**.log";
        $file = escapeshellarg($logs_route);
        $line = "tail -n 3 $file 2>&1";

        session()->flash('exchange_logs', "$line<br/>\n");

        // $i = 1;
        // while (1) {
        //     // dd($line);
        //     // $output = shell_exec(escapeshellarg($line));
        //     session()->flash('debug_info', $line);
        //     sleep(5);
        //     $i++;
        // }

    }
}
