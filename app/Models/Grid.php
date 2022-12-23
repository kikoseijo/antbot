<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Grid extends Model
{
    use HasFactory, Traits\ScopeMineTrait;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function saveConfigToDisk()
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => $this->storage_path,
        ]);

        $disk->put($this->file_name, $this->grid_json);
    }

    public function getFilePathAttribute()
    {
        return "{$this->storage_path}/{$this->file_name}";
    }

    public function getFileNameAttribute()
    {
        $base_name = Str::upper(Str::slug($this->name));

        return "$base_name.json";
    }

    public function getStoragePathAttribute()
    {
        $bot_path = config('antbot.paths.bot_path');
        $u_id = $this->user->id;

        return "$bot_path/configs/live/$u_id";
    }
}
