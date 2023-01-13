<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;
    public $title = 'Users';

    protected $rules = [
        'user.name' => 'required|string|min:4',
        'user.role' => 'required',
        'user.email' => 'required|string',
        'user.admin' => 'sometimes',
        'user.timezone' => 'required',
    ];

    public function render()
    {
        $cur_user = auth()->user();
        if (!$cur_user->isAdmin() && $this->user->id != $cur_user->id) {

            return abort(403, 'Unauthorized action.');
        }

        $data = [
            'user_roles' => config('antbot.roles'),
            'timezones' => \DateTimeZone::listIdentifiers()
        ];

        return view('livewire.users.edit-user', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();
        $this->validate([
            'user.email' => 'email|unique:users,email,'.$this->user->id,
        ]);

        $this->user->save();

        return redirect()->route('users.index');
    }
}
