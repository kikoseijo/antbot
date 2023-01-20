<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function getNiceNameAttribute()
    {
        return str_replace('_UMCBL', '', $this->symbol);
    }
}
