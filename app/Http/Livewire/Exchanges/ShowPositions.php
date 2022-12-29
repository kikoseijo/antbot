<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPositions extends Component
{
    use WithPagination;
    public Exchange $exchange;
    public $title = 'Exchanges';

    public function render()
    {
        if ($this->exchange->user_id != auth()->user()->id) {
            return abort(403, 'Unauthorized action.');
        }

        $data = [
            'balances' => $this->exchange->balances,
            // 'positions' => $this->exchange->positions()->with('exchange', 'orders')->withCount('buy_orders', 'sell_orders')->paginate(20),
        ];

        return view('livewire.exchanges.show-positions', $data)->layoutData([
            'title' => $this->title,
        ]);
    }
}
