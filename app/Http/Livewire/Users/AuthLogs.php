<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class AuthLogs extends Component
{
    public User $user;
    public $title = 'User auth logs';

    public function render()
    {
        $cur_user = auth()->user();
        if (!$cur_user->isAdmin() && $this->user->id != $cur_user->id) {
            return abort(403, 'Unauthorized');
        }

        return view('livewire.users.auth-logs')->layoutData([
            'title' => $this->title,
        ]);
    }
}
