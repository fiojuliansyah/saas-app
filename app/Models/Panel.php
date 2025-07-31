<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panel extends Model
{
    use HasFactory;

    protected $fillable = [
        'battle_id',
        'title',
        'timeout_time',
    ];


    public function battle()
    {
        return $this->belongsTo(Battle::class, 'battle_id');
    }
}
