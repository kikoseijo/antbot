<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

use App\Routines\ExecuteRoutine;


class Routine extends Model
{
    use HasUuids;
    use HasFactory;
    use Traits\ScopeMineTrait;
    use Traits\Historicable;

    protected $guarded = ['id'];
    protected $dates = ['triggered_at']; //, 'end_scheduled_at'

    protected $casts = [
        'action' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStopScheduled( $query)
    {
        return $query->where('is_end_scheduled', 1);
    }

    public function execute($trigger_by, $sleep_time = 1)
    {
        $executor = (new ExecuteRoutine)
            ->runRoutine($this, $sleep_time);
        $this->triggered_at = now();
        $this->triggered_by = $trigger_by;
        $this->save();
    }

    public function getWebhookUrlAttribute()
    {
        return route('api.routines.run', $this);
    }
}
