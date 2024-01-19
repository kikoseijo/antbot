<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Exchange;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPositions extends Component
{
    use WithPagination;
    public $title;
    public $total_wallet_exposure;

    public function mount()
    {
        $exchange = auth()->user()->exchange;
        if (!$exchange) {
            session()->flash('message', 'Please create your first exchange.');
            return redirect()->route('exchanges.add');
        }

        $this->title = 'Active positions';

        $res = [];
        // dd($this->exchange->balances->toArray()[1]['wallet_balance']);
        foreach ($exchange->balances as $balance) {
            $res[$balance->symbol] = 0;
            foreach ($exchange->positions as $position) {
                $res[$balance->symbol] += ($position->size * $position->entry_price) / $balance->wallet_balance;
            }
        }

        $this->total_wallet_exposure = $res;
    }

    public function render()
    {


        $data = [
            'balances' => auth()->user()->exchange->balances,
        ];

        return view('livewire.exchanges.show-positions', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

}
