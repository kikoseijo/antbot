<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['created_time', 'updated_time'];

    // protected $casts = [
    //     'grid_json' => 'json',
    // ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}
