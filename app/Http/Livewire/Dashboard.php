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
            ->withCount('bots', 'positions')
            ->get();

        $data = [
            'exchanges' => $exchanges
        ];

        return view('livewire.dashboard', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function showPositions($exchange_id)
    {
        $this->setExchange($exchange_id);

        return redirect()->route('positions.index');
    }

    public function showTrades($exchange_id)
    {
        $this->setExchange($exchange_id);

        return redirect()->route('trades.pnl');
    }

    public function showBots($exchange_id)
    {
        $this->setExchange($exchange_id);

        return redirect()->route('bots.index');
    }

    protected function setExchange($exchange_id)
    {
        if ($exchange_id == auth()->user()->exchange_id) {

            return ;
        }

        $exch_ids = auth()->user()->exchanges->pluck('id');
        if (in_array($exchange_id, $exch_ids->all())) {
            auth()->user()->update(['exchange_id' => $exchange_id]);
        }
    }

    public function showLogs(Exchange $exchange)
    {
        $logs_path = config('antbot.paths.passivbot_logs');
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
