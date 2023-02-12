<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Symbol;
use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;

class TradeRecords extends Component
{
    use WithPagination;

    public Exchange $exchange;
    public $symbol = '';
    public $title = 'Trade records';

    public $from_date = "";
    public $to_date = "";

    public $orderColumn = "created_at";
    public $sortOrder = "desc";

    protected $rules = [
        'symbol.id' => 'required',
    ];

    public function updated(){
         $this->resetPage();
    }

    public function mount(Exchange $exchange, $symbol){
         $this->exchange = $exchange;
         $this->symbol = $symbol;
    }

    public function render()
    {
        $query = $this->exchange->trades()
          ->orderby($this->orderColumn, $this->sortOrder);
        if (isset($this->symbol)){
          $query->where('symbol', $this->symbol);
        }
        if ($this->from_date != ''){
          $query->where('created_at', '>=', $this->from_date);
        }
        if ($this->to_date != ''){
          $query->where('created_at', '<=', $this->to_date);
        }
        $data = [
          'records' => $query->paginate(50),
          'symbols' => Symbol::where('exchange', $this->exchange->exchange->value)
              ->select('id', 'name')
              ->pluck('name', 'id')
        ];

        return view('livewire.exchanges.trade-records', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

}
