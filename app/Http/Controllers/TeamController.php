<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Support\Str;
use App\Imports\TeamsImport;
use Illuminate\Http\Request;
use App\DataTables\TeamsDataTable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TeamsDataTable $dataTable)
    {
        return $dataTable->render('teams.index');
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
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'country' => 'nullable|string|max:100',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $tenantUser = Auth::user()->tenant_id;

        // Simpan ke database
        $team = Team::create([
            'tenant_id' => $tenantUser,
            'slug' => Str::slug($request->name),
            'tenant_id' => Auth::user()->tenant_id ?? 'default',
            'logo' => $logoPath,
            'name' => strtoupper($request->name),
            'short_name' => strtoupper($request->short_name),
            'country' => $request->country,
        ]);

        return redirect()->back()->with('success', 'Team created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($team->logo && \Storage::disk('public')->exists($team->logo)) {
                \Storage::disk('public')->delete($team->logo);
            }
            $team->logo = $request->file('logo')->store('logos', 'public');
        }

        $team->update([
            'name' => strtoupper($validated['name']),
            'short_name' => strtoupper($validated['short_name']),
            'country' => $validated['country'],
        ]);

        return redirect()->back()->with('success', 'Team updated successfully!');
    }

    public function destroy(Team $team)
    {
        if ($team->logo && \Storage::disk('public')->exists($team->logo)) {
            \Storage::disk('public')->delete($team->logo);
        }

        $team->delete();

        return redirect()->back()->with('success', 'Team deleted successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TeamsImport, $request->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             return back()->withErrors($failures)->with('error', 'Ada beberapa data yang gagal diimpor.');
        }

        return redirect()->back()->with('success', 'Semua tim berhasil diimpor!');
    }
}
