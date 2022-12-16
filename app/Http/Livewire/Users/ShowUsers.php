<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class ShowUsers extends Component
{
    public function render()
    {
        return view('livewire.users.show-users', [
            'users' => User::all()
        ]);
    }
}
