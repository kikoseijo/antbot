<?php

namespace App\Http\Livewire\Symbols;

use Livewire\Component;

class WhatToTrade extends Component
{
    public $title = 'What2Trade';

    public function render()
    {
        return view('livewire.symbols.what-to-trade')->layoutData([
            'title' => $this->title,
        ])
        // ->layout('layouts.guest-antbot')
        ;
    }
}
