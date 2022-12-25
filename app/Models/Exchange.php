<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLongWalletExposureAttribute()
    {
        $level = $this->bots->sum('lwe');

        return $level;
    }

    public function getShortWalletExposureAttribute()
    {
        $level = $this->bots->sum('swe');

        return $level;
    }

    public function getMaxExposureAttribute()
    {
        return match($this->risk_mode){
            2 => 2.5,
            3 => 5,
            default => 1.8,
        };
    }
}
