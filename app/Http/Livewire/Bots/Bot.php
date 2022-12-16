<?php

namespace App\Http\Livewire\Bots;

use Livewire\Component;
use App\Models\Bot as AntBot;

class Bot extends Component
{
    public $records, $name, $email, $selected_id;
    public $updateMode = false;
    public $viewsFolder = 'bots';
    public $header = 'Bots';

    public function render()
    {
        $this->data = AntBot::all();
        return view('livewire.crud', [ 'header' => 'Bots' ]);
    }

    private function resetInput()
    {
        $this->name = null;
        $this->email = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        AntBot::create([
            'name' => $this->name,
            'email' => $this->email
        ]);
        $this->resetInput();
    }

    public function edit($id)
    {
        $record = AntBot::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        if ($this->selected_id) {
            $record = AntBot::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email
            ]);
            $this->resetInput();
            $this->updateMode = false;
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = AntBot::where('id', $id);
            $record->delete();
        }
    }
}
