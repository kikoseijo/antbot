<?php

namespace App\Http\Livewire\Traits;

trait LivewireToast
{
    protected function toast($msg, $type = 'info')
    {
        $this->dispatchBrowserEvent('alert',[
            'type' => $type,
            'message' => $msg
        ]);
    }
}
