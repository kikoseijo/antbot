<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use Livewire\Component;

class ShowTrades extends Component
{
    public Exchange $exchange;
    public $title;

    public function mount()
    {
        $this->title = $this->exchange->name . " - Trades";
    }

    public function render()
    {
        if ($this->exchange->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $trades = $this->exchange->trades()->where('created_at', '>', '2022-9-30 23:59:59')
                ->selectRaw(
                    'year(created_at) year, month(created_at) month, count(*) total_trades, sum(closed_pnl) pnl, symbol, monthname(created_at) month_name'
                )
                ->groupBy('year', 'month', 'symbol', 'month_name')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderBy('symbol', 'asc')
                ->get();

        $res = [];
        $dates = [];
        foreach ($trades as $trade) {
            $fecha = substr($trade->month_name, 0, 3) . ' - ' . $trade->year;
            if (!in_array($fecha, $dates)) {
                array_push($dates, $fecha);
            }
            $res[$trade->symbol][$fecha] = [
                'date' => $fecha,
                'trades_count' => $trade->total_trades,
                'pnl' => $trade->pnl,
            ];
        }

        // dd($res, $dates);

        $data = [
            'records' => $res,
            'dates' => $dates,
            // 'positions' => $this->exchange->positions()->with('exchange', 'orders')->withCount('buy_orders', 'sell_orders')->paginate(20),
        ];

        return view('livewire.exchanges.show-trades', $data)->layoutData([
            'title' => $this->title,
        ]);
    }
}
