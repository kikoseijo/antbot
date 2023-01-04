<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPositions extends Component
{
    use WithPagination;
    public Exchange $exchange;
    public $title = 'Exchanges';
    public $total_wallet_exposure;

    public function mount()
    {
        $res = [];
        // dd($this->exchange->balances->toArray()[1]['wallet_balance']);
        foreach ($this->exchange->balances as $balance) {
            $res[$balance->symbol] = 0;
            foreach ($this->exchange->positions as $position) {
                $res[$balance->symbol] += ($position->size * $position->entry_price) / $balance->wallet_balance;
            }
        }

        $this->total_wallet_exposure = $res;
    }

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

    public function functionName()
    {

    }
}
