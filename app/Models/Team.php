<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'tenant_id',
        'logo',
        'name',
        'short_name',
        'country',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
