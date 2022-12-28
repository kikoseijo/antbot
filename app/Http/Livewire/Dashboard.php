<?php

namespace App\Http\Livewire;

use App\Models\Exchange;
use Livewire\Component;

class Dashboard extends Component
{
    public $title = 'Dashboard';

    public function render()
    {
        $exchanges = Exchange::mine()
            ->where('name', 'kkkk')
            ->with('bots')
            ->withCount('bots')
            ->get();
        $data = [
            'exchanges' => $exchanges
        ];

        return view('livewire.dashboard', $data)->layoutData([
            'title' => $this->title,
        ]);
    }
}
