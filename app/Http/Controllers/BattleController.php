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
    public function index(BattlesDataTable $dataTable)
    {
        $teams = Team::all();
        return $dataTable->render('battles.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_a_id' => 'required|different:team_b_id',
            'team_b_id' => 'required',
            'score_team_a' => 'nullable|integer',
            'score_team_b' => 'nullable|integer',
            'match_date' => 'required|date',
            'match_time' => 'required|date',
        ]);

        $tenantUser = Auth::user()->tenant_id;

        $room_code = Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4);

        Battle::create([
            'tenant_id' => $tenantUser,
            'team_a_id' => $request->team_a_id,
            'team_b_id' => $request->team_b_id,
            'score_team_a' => $request->score_team_a,
            'score_team_b' => $request->score_team_b,
            'match_date' => $request->match_date,
            'match_time' => $request->match_time,
            'room_code' => $room_code, 
        ]);

        return redirect()->back()->with('success', 'Battle created');
    }

    public function update(Request $request, Battle $battle)
    {
        $request->validate([
            'team_a_id' => 'required|different:team_b_id',
            'team_b_id' => 'required',
            'score_team_a' => 'nullable|integer',
            'score_team_b' => 'nullable|integer',
            'match_date' => 'required|date',
            'match_time' => 'required|date',
        ]);

        $battle->update([
            'team_a_id' => $request->team_a_id,
            'team_b_id' => $request->team_b_id,
            'score_team_a' => $request->score_team_a,
            'score_team_b' => $request->score_team_b,
            'match_date' => $request->match_date,
            'match_time' => $request->match_time,
        ]);

        return redirect()->back()->with('success', 'Battle updated successfully');
    }

    public function destroy(Battle $battle)
    {
        $battle->delete();

        return redirect()->back()->with('success', 'Battle deleted successfully');
    }
}
