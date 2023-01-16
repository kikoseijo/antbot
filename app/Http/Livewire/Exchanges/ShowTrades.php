<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use Livewire\Component;

class ShowTrades extends Component
{
    public Exchange $exchange;
    public $title;
    public $chart_type = 'monthly';

    protected $rules = [
        'chart_type' => 'required',
    ];

    public function mount()
    {
        $this->title = $this->exchange->name . " - PNL";
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
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
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
        $records = $this->exchange->trades()->where('created_at', '>', '2022-9-30 23:59:59')
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
            $res[$trade->symbol][$fecha] = [
                'date' => $fecha,
                'trades_count' => $trade->total_trades,
                'pnl' => $trade->pnl,
            ];
        }

        return [
            'records' => $res,
            'dates' => $dates,
        ];
    }
}
