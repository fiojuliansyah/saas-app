<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 
        'tenant_id', 
        'team_id', 
        'avatar', 
        'name', 
        'nickname', 
        'country',
        'squad',
        'role',
        'active'
    ];


    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
