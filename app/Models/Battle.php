<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_a_id',
        'tenant_id',
        'team_b_id',
        'score_team_a',
        'score_team_b',
        'match_datetime',
        'room_code',
        'map_a',
        'map_b',
        'map_decider'
    ];

    protected $dates = ['match_datetime'];

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function panel()
    {
        return $this->hasOne(Panel::class);
    }
}
