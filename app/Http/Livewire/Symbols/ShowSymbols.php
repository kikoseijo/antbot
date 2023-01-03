<?php

namespace App\Http\Livewire\Symbols;

use Livewire\Component;

class ShowSymbols extends Component
{
    public $title = 'Symbols';

    public function render()
    {
        return view('livewire.symbols.show-symbols')->layoutData([
            'title' => $this->title,
        ]);
    }
}
