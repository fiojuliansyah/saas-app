<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Format;
use Illuminate\Http\Request;

class OverlayController extends Controller
{
    public function getOverlayData($roomCode)
    {
        $match = Battle::where('room_code', $roomCode)
                       ->with(['teamA', 'teamB', 'panel'])
                       ->firstOrFail();

        $data = [
            'match_title' => $match->panel->title ?? '',
            'score_team_a' => $match->score_team_a ?? 0,
            'score_team_b' => $match->score_team_b ?? 0,
            'name_team_a'  => $match->teamA->short_name,
            'name_team_b'  => $match->teamB->short_name,
            
            'logo_team_a'  => asset('storage/' . $match->teamA->logo),
            'logo_team_b'  => asset('storage/' . $match->teamB->logo),
            'flag_team_a'  => asset('assets/images/flags/' . ($match->teamA->country ?? 'default') . '.png'),
            'flag_team_b'  => asset('assets/images/flags/' . ($match->teamB->country ?? 'default') . '.png'),
            
        ];

        return response()->json($data);
    }
    
    public function cover($roomCode)
    {
        $covermatch = Battle::where('room_code', $roomCode)->firstOrFail();
        
        return view('overlay.delta-force-v1.cover', compact('covermatch'));
    }
    
    public function topbar($roomCode)
    {
        $topbarmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        
        return view('overlay.delta-force-v1.topbar', compact('topbarmatch'));
    }

    public function swapsides($roomCode)
    {
        $swapsidesmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        
        return view('overlay.delta-force-v1.swap-sides', compact('swapsidesmatch'));
    }

    public function getSwapData($roomCode)
    {
        $match = Battle::where('room_code', $roomCode)->with(['teamA', 'teamB'])->firstOrFail();

        $dataState = [
            'team_a_id' => $match->team_a_id,
            'team_b_id' => $match->team_b_id,
            'team_a_name' => $match->teamA->name,
            'team_b_name' => $match->teamB->name,
            'team_a_logo' => $match->teamA->logo,
            'team_b_logo' => $match->teamB->logo,
            'status' => $match->status,
        ];
        $stateHash = md5(json_encode($dataState));

        return response()->json([
            'stateHash' => $stateHash,
            'teamA' => [
                'name' => $match->teamA->name,
                'logo_url' => asset('storage/' . $match->teamA->logo)
            ],
            'teamB' => [
                'name' => $match->teamB->name,
                'logo_url' => asset('storage/' . $match->teamB->logo)
            ]
        ]);
    }

    public function banmaps($roomCode)
    {
        $banmapmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        
        return view('overlay.delta-force-v1.ban-map', [
            'banmapmatch' => $banmapmatch,
            'roomCode' => $roomCode
        ]);
    }

    public function timeout($roomCode)
    {
        $timeoutmatch = Battle::with('panel')->where('room_code', $roomCode)->firstOrFail();
        
        return view('overlay.delta-force-v1.timeout', compact('timeoutmatch', 'roomCode'));
    }

    public function transitionV1()
    {        
        return view('overlay.delta-force-v1.transition-v1');
    }

    public function transitionV2()
    {        
        return view('overlay.delta-force-v1.transition-v2');
    }

    public function replay($roomCode)
    {        
        return view('overlay.delta-force-v1.replay');
    }

    public function gameFormat($roomCode)
    {
        $format = Format::first();

        return view('overlay.delta-force-v1.game-format',compact('format'));
    }

    public function team($roomCode)
    {
        $teammatch = Battle::with([
                            'panel', 
                            'teamA.players', 
                            'teamB.players'
                        ])
                        ->where('room_code', $roomCode)
                        ->firstOrFail();

        $squadTeamA = $teammatch->teamA->players->groupBy('squad');
        $squadTeamB = $teammatch->teamB->players->groupBy('squad');
        
        return view('overlay.delta-force-v1.team', compact(
            'teammatch', 
            'roomCode', 
            'squadTeamA', 
            'squadTeamB'
        ));
    }
}
