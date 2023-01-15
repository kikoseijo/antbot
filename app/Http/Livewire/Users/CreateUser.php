<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class CreateUser extends Component
{
    public User $user;
    public $title = 'Users';

    protected $rules = [
        'user.name' => 'required|string|min:4',
        'user.email' => 'required|string|max:500|unique:users,email',
        'user.role' => 'required',
        'user.admin' => 'sometimes',
        'user.timezone' => 'required',
    ];

    public function mount()
    {
        $this->user = new User();
        $this->user->timezone = 'Europe/Madrid';
        $this->user->admin = 0;
        $this->user->role = 2;
    }



    public function render()
    {
        if (!auth()->user()->isAdmin()) {

            return abort(403, 'Unauthorized');
        }

        $data = [
            'user_roles' => config('antbot.roles'),
            'timezones' => \DateTimeZone::listIdentifiers()
        ];

        return view('livewire.users.create-user', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function submit()
    {
        $this->validate();

        $this->user->password = time();

        $this->user->save();

        return redirect()->route('users.index');
    }
}
