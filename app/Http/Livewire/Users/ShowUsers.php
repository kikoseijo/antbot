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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $records = [];
        if (auth()->user()->role == 1 && auth()->user()->admin)
            $records = User::where('name', 'like', '%'.$this->search.'%')->paginate(5);

        return view('livewire.users.show-users', [
            'records' => $records
        ]);
    }

    public function deleteId($id)
    {
        $this->deleteId = $id;
    }

    public function destroy()
    {
        if ($this->deleteId > 0) {
            $record = User::find($this->deleteId);
            if(auth()->user()->admin && auth()->user()->role == 1){
                $record->delete();
                session()->flash('message', 'User account successfully deleted.');
            }
            $this->deleteId = 0;
        }
    }
}
