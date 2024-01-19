<?php

namespace App\Models\Traits;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Historicable
{
    public function historic(): MorphMany
    {
        return $this->morphMany(History::class, 'historicable');
    }
}
