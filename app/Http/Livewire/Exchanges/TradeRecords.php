<?php

namespace App\Http\Livewire\Exchanges;

use App\Models\Symbol;
use App\Models\Exchange;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class TradeRecords extends Component
{
    use WithPagination;

    public $select_symbol = '';
    public $title = 'Trades by Symbol';

    public $from_date = null;
    public $to_date = null;

    public $orderColumn = "created_at";
    public $sortOrder = "desc";
    public $n_records = 10;

    protected $rules = [
        'select_symbol' => 'required',
        'n_records' => 'required',
        // 'from_date' => 'required',
        // 'to_date' => 'required',
    ];

    public function updated()
    {
        $this->resetPage();
    }

    public function mount($symbol = null)
    {
        if (!auth()->user()->exchange_id) {
            session()->flash('message', 'Please create your first exchange.');

            return redirect()->route('exchanges.add');
        }
        $this->select_symbol = $symbol;
    }

    public function render()
    {
        $exchange = auth()->user()->exchange;
        $query = $exchange->trades()->orderby($this->orderColumn, $this->sortOrder);
        if (isset($this->select_symbol)) {
            $query->where('symbol', $this->select_symbol);
        }
        if (!is_null($this->from_date)) {
            $query->where('created_at', '>=', $this->from_date);
        }
        if (!is_null($this->to_date)) {
            logi($this->to_date);
            $query->where('created_at', '<=', $this->to_date);
        }
        $exchange_id = $exchange->id;
        $data = [
          'records' => $query->paginate($this->n_records),
          'symbols' => Symbol::where('exchange', $exchange->exchange->value)
              ->whereHas('trades', function (Builder $query) use ($exchange_id) {
                  $query->where('exchange_id', $exchange_id);
              })
              ->select('id', 'name')
              ->orderBy('name', 'asc')
              ->pluck('name', 'id')
        ];

        return view('livewire.exchanges.trade-records', $data)->layoutData([
            'title' => $this->title,
        ]);
    }
}
