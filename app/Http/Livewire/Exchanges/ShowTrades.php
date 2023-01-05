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

        $trades = $this->exchange->trades()->selectRaw(
                    'year(created_at) year, month(created_at) month, count(*) total_trades, sum(closed_pnl) pnl, symbol, monthname(created_at) month_name'
                )
                ->groupBy('year', 'month', 'symbol', 'month_name')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderBy('symbol', 'desc')
                ->paginate(25);

        $data = [
            'records' => $trades,
            // 'positions' => $this->exchange->positions()->with('exchange', 'orders')->withCount('buy_orders', 'sell_orders')->paginate(20),
        ];

        return view('livewire.exchanges.show-trades', $data)->layoutData([
            'title' => $this->title,
        ]);
    }
}
