<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;

    public function render()
    {
        $cur_user = auth()->user();
        if (!$cur_user->isAdmin() && $this->user->id != $cur_user->id) {
            return abort(403, 'Unauthorized action.');
        }

        return view('livewire.users.edit-user');
    }
}
