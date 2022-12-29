<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}
