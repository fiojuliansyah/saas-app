<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\PlayersImport;
use App\DataTables\PlayersDataTable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PlayersDataTable $dataTable)
    {
        $teams = Team::all();
        return $dataTable->render('players.index', compact('teams'));
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
        $validated = $request->validate([
            'team_id' => 'required|string',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'squad' => 'nullable|string',
            'role' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $tenantUser = Auth::user()->tenant_id;

        Player::create([
            'tenant_id' => $tenantUser,
            'slug' => Str::slug($request->name),
            'tenant_id' => Auth::user()->tenant_id ?? 'default',
            'team_id' => $request->team_id,
            'avatar' => $avatarPath,
            'name' => strtoupper($request->name),
            'nickname' => $request->nickname,
            'country' => $request->country,
            'squad' => $request->squad,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Player created successfully!');
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'team_id' => 'required|string',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'squad' => 'nullable|string',
            'role' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($player->avatar && \Storage::disk('public')->exists($player->avatar)) {
                \Storage::disk('public')->delete($player->avatar);
            }
            $player->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $player->update([
            'team_id' => $request->team_id,
            'name' => strtoupper($request->name),
            'nickname' => $request->nickname,
            'country' => $request->country,
            'squad' => $request->squad,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Player updated successfully!');
    }

    public function destroy(Player $player)
    {
        if ($player->avatar && \Storage::disk('public')->exists($player->avatar)) {
            \Storage::disk('public')->delete($player->avatar);
        }

        $player->delete();

        return redirect()->back()->with('success', 'Player deleted successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new PlayersImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             return back()->withErrors($failures)->with('error', 'Ada beberapa data yang gagal diimpor.');
        }

        return redirect()->back()->with('success', 'Semua tim berhasil diimpor!');
    }


}
