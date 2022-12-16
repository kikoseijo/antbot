<?php

namespace App\Models\Traits;

trait ScopeMineTrait {

    public function scopeMine( $query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
