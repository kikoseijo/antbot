<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class ShowUsers extends Component
{
    public function render()
    {
        return view('livewire.show-users', [
            'users' => User::all()
        ]);
    }
}
