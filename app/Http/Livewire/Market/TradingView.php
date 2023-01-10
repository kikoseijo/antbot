<?php

namespace App\Http\Livewire\Market;

use App\Models\Position;
use Livewire\Component;
use Ksoft\Bybit\BybitLinear;

class TradingView extends Component
{
    public Position $position;
    public $interval; // 1 3 5 15 30 60 120 240 360 720 "D" "M" "W"

    public $title = 'Bybit positions';

    public function mount($interval)
    {
        $this->interval = $interval;
    }

    public function render()
    {
        if ($this->position->exchange->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $orders = $this->position->orders()->select(['side', 'price', 'qty'])->get();
        $precision = $this->position->coin->price_scale;

        $data = [
            'precision' => $precision,
            'orders' => $orders->toArray(),
            'entry_order' => [
                'price' => number($this->position->entry_price, $precision),
                'qty' => number($this->position->size, 1),
            ]
        ];

        return view('livewire.market.trading-view', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

}
