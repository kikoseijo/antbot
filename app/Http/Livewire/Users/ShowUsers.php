<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId = 0;
    public $title = 'Users';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!auth()->user()->isAdmin()) {

            return abort(403, 'Unauthorized action.');
        }

        $data = [
            'user_roles' => config('antbot.roles'),
            'records' => User::where('name', 'like', '%'.$this->search.'%')
                    ->withCount('bots', 'grids', 'exchanges')
                    ->paginate(10)
        ];

        return view('livewire.users.show-users', $data)->layoutData([
            'title' => $this->title,
        ]);
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $user = User::find($this->deleteId);
            if(auth()->user()->isAdmin()){
                $user->destroyResources();
                $user->delete();
                session()->flash('message', 'User account successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }


}
