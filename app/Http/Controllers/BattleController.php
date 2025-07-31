<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Battle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\BattlesDataTable;
use Illuminate\Support\Facades\Auth;

class BattleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BattlesDataTable $dataTable)
    {
        $teams = Team::all();
        return $dataTable->render('battles.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_a_id' => 'required|different:team_b_id',
            'team_b_id' => 'required',
            'score_team_a' => 'nullable|integer',
            'score_team_b' => 'nullable|integer',
            'match_datetime' => 'required|date',
        ]);

        $tenantUser = Auth::user()->tenant_id;

        $room_code = Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4);

        Battle::create([
            'tenant_id' => $tenantUser,
            'team_a_id' => $request->team_a_id,
            'team_b_id' => $request->team_b_id,
            'score_team_a' => $request->score_team_a,
            'score_team_b' => $request->score_team_b,
            'match_datetime' => $request->match_datetime,
            'room_code' => $room_code, 
        ]);

        return redirect()->back()->with('success', 'Battle created');
    }
    /**
     * Display the specified resource.
     */
    public function show(Battle $battle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Battle $battle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Battle $battle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Battle $battle)
    {
        //
    }
}
