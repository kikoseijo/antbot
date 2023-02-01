<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Enums\ExchangesEnum;
use Livewire\Component;
use Carbon\Carbon;

class ShowTrades extends Component
{
    public Exchange $exchange;
    public $title;
    public $chart_type = 'daily';
    public $search_month;
    public $search_year;

    protected $rules = [
        'chart_type' => 'required',
        'search_month' => 'required',
        'exchange.id' => 'required',
    ];

    protected $listeners = ['refreshTradesComponent' => '$refresh'];

    public function mount()
    {
        $this->title = $this->exchange->name . " - PNL";
        $this->search_month = now()->month;
        $this->search_year = now()->year;
    }

    public function render()
    {
        if ($this->exchange->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized');
        }

        $data = $this->chart_type == 'monthly' ? $this->montlyRecords() : $this->dailyRecords();

        return view('livewire.exchanges.show-trades', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    protected function dailyRecords()
    {
        $records = $this->exchange->trades()
                ->whereMonth('created_at', $this->search_month)
                ->whereYear('created_at', $this->search_year)
                ->selectRaw(
                    'month(created_at) month, day(created_at) year, count(*) total_trades, sum(closed_pnl) pnl, symbol, monthname(created_at) month_name'
                )
                ->groupBy('month', 'year', 'symbol', 'month_name')
                ->orderBy('month', 'desc')
                ->orderBy('year', 'desc')
                ->orderBy('symbol', 'asc')
                ->get();

        return $this->parsedRecords($records);
    }

    protected function montlyRecords()
    {
        $records = $this->exchange->trades()->whereYear('created_at', $this->search_year)
                ->selectRaw(
                    'year(created_at) year, month(created_at) month, count(*) total_trades, sum(closed_pnl) pnl, symbol, monthname(created_at) month_name'
                )
                ->groupBy('year', 'month', 'symbol', 'month_name')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->orderBy('symbol', 'asc')
                ->get();

        return $this->parsedRecords($records);
    }

    protected function parsedRecords($records)
    {
        $res = [];
        $dates = [];
        foreach ($records as $trade) {
            $fecha = substr($trade->month_name, 0, 3) . ' - ' . $trade->year;
            if (!in_array($fecha, $dates)) {
                array_push($dates, $fecha);
            }
            $res[$trade->nice_name][$fecha] = [
                'date' => $fecha,
                'trades_count' => $trade->total_trades,
                'pnl' => $trade->pnl,
            ];
        }

        ksort($res);

        return [
            'records' => $res,
            'dates' => $dates,
        ];
    }
}
