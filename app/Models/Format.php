<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Format extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'team_format',
        'win_condition',
        'game_mode',
        'game_1',
        'game_2',
        'game_3',
    ];
}