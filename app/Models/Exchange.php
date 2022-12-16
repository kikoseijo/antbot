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
}
