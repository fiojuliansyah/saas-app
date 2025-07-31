<?php

namespace App\Http\Controllers;

use App\Models\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormatController extends Controller
{

    public function createOrEdit()
    {
        $format = Format::firstOrNew(['tenant_id' => Auth::user()->tenant_id]);
        return view('formats.index', compact('format'));
    }

    public function updateOrCreate(Request $request)
    {
        $validated = $request->validate([
            'team_format'   => 'required|in:warefare,operation',
            'win_condition' => 'nullable|string',
            'game_mode'     => 'nullable|string',
            'game_1'        => 'nullable|string',
            'game_2'        => 'nullable|string',
            'game_3'        => 'nullable|string',
        ]);

        Format::updateOrCreate(
            [
                'tenant_id' => Auth::user()->tenant_id,
            ],
            $validated
        );

        return redirect()->back()
                         ->with('success', 'Format berhasil disimpan!');
    }
}