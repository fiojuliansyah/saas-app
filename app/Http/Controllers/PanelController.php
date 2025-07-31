<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use Illuminate\Http\Request;
use App\DataTables\PanelsDataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PanelController extends Controller
{
    public function index(PanelsDataTable $dataTable)
    {
        return $dataTable->render('panels.index');
    }

    public function panelController($roomCode)
    {
        $panelmatch = Battle::with('panel')
                            ->where('room_code', $roomCode)
                            ->where('tenant_id', Auth::user()->tenant_id)
                            ->first();

        if (!$panelmatch) {
            return redirect()->route('dashboard')
                            ->with('error', 'Anda tidak memiliki izin untuk mengakses panel ini.');
        }

        return view('panels.panel-controller', compact('panelmatch'));
    }

    public function updateTitle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'battle_id' => 'required|exists:battles,id',
            'title'     => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $battle = Battle::findOrFail($request->input('battle_id'));

            $battle->panel()->updateOrCreate(
                [],
                ['title' => $request->input('title')] 
            );

            return response()->json(['success' => true, 'message' => 'Panel title updated!']);

        } catch (Throwable $e) {
            Log::error('Error updating panel title: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error.'], 500);
        }
    }

    public function updateScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'battle_id' => 'required|exists:battles,id',
            'team'      => 'required|in:a,b',
            'score'     => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $battle = Battle::findOrFail($request->input('battle_id'));

            $scoreColumn = 'score_team_' . $request->input('team');

            $battle->{$scoreColumn} = $request->input('score');
            $battle->save();

            return response()->json([
                'success' => true,
                'message' => 'Score for team ' . strtoupper($request->input('team')) . ' updated successfully!',
            ]);

        } catch (Throwable $e) {
            Log::error('Error updating score: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred. Please check the logs.'
            ], 500);
        }
    }

    public function swapSides(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'battle_id' => 'required|exists:battles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $battle = Battle::findOrFail($request->input('battle_id'));

            $tempTeamId = $battle->team_a_id;
            $tempScore = $battle->score_team_a;

            $battle->team_a_id = $battle->team_b_id;
            $battle->score_team_a = $battle->score_team_b;

            $battle->team_b_id = $tempTeamId;
            $battle->score_team_b = $tempScore;

            $battle->save();

            return response()->json([
                'success' => true,
                'message' => 'Teams and scores have been swapped successfully!',
            ]);

        } catch (Throwable $e) {
            Log::error('Error swapping sides: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred while swapping sides.'
            ], 500);
        }
    }

    private $availableMaps = [
        'CyClone', 'Train Wreck', 'Knife Edge', 'Threshold',
        'Ascension', 'Cracked', 'Trench Lines', 'Shafted'
    ];

    private function getCacheKey(Battle $battle)
    {
        return 'battle_' . $battle->id . '_state';
    }

    public function showPickMapPanel($roomCode, $teamIdentifier)
    {
        if (!in_array(strtolower($teamIdentifier), ['a', 'b'])) {
            abort(404);
        }

        session(['my_team_identifier' => strtoupper($teamIdentifier)]);
        $panelmatch = Battle::where('room_code', $roomCode)->firstOrFail();

        // Tidak ada lagi logika Cache::remember di sini.
        // Kita biarkan getState yang mengurus semuanya.

        return view('panels.pick-maps-controller', [
            'panelmatch' => $panelmatch,
            'myTeam' => strtoupper($teamIdentifier)
        ]);
    }

    public function getState($roomCode)
    {
        $panelmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        $state = $this->handleTimeoutAndGetState($panelmatch);
        return response()->json($state);
    }

    // Helper method yang dibutuhkan oleh getState (pastikan ini juga ada)
    private function handleTimeoutAndGetState(Battle $battle)
    {
        $cacheKey = $this->getCacheKey($battle);
        $state = Cache::get($cacheKey);

        if (!$state) {
            $state = [
                'phase' => 'waiting',
                'turn' => null,
                'turn_starts_at' => null,
                'bans' => [],
                'picks' => [],
                'team_a_bans' => [],
                'team_b_bans' => [],
                'team_a_pick' => null,
                'team_b_pick' => null,
                'last_update' => now()->timestamp,
            ];
            Cache::put($cacheKey, $state, now()->addHours(2));
            return $state;
        }

        if (in_array($state['phase'], ['ban', 'pick'])) {
            if ((now()->timestamp - $state['turn_starts_at']) > 30) {
                $state = $this->performRandomAction($state);
                Cache::put($cacheKey, $state, now()->addHours(2));
            }
        }

        return $state;
    }

    public function startBanPick(Request $request, $roomCode)
    {
        $battle = Battle::where('room_code', $roomCode)->firstOrFail();
        $cacheKey = $this->getCacheKey($battle);

        $initialState = [
            'phase' => 'ban',
            'turn' => 'A',
            'turn_starts_at' => now()->timestamp,
            'bans' => [],
            'picks' => [],
            'team_a_bans' => [],
            'team_b_bans' => [],
            'team_a_pick' => null,
            'team_b_pick' => null,
            'last_update' => now()->timestamp,
        ];
        
        Cache::put($cacheKey, $initialState, now()->addHours(2));

        return response()->json(['success' => true, 'message' => 'Proses Ban/Pick dimulai!']);
    }


public function handleTeamAAction(Request $request, $roomCode)
    {
        // Memastikan request datang dari sesi 'A'
        if (session('my_team_identifier') !== 'A') {
            return response()->json(['message' => 'Aksi tidak diizinkan. Akses dari link Tim A.'], 403);
        }

        $panelmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        $state = $this->handleTimeoutAndGetState($panelmatch);

        // Memastikan state di cache adalah giliran 'A'
        if ($state['turn'] !== 'A') {
            return response()->json(['message' => 'Bukan giliran Tim A.'], 403);
        }

        $mapName = $request->validate(['mapName' => 'required|string'])['mapName'];
        $state = $this->processAction($state, $mapName);
        
        Cache::put($this->getCacheKey($panelmatch), $state, now()->addHours(2));

        if($state['phase'] === 'end') {
            $this->savePicksToDb($panelmatch, $state['team_a_pick'], $state['team_b_pick']);
        }
        
        return response()->json($state);
    }

    public function handleTeamBAction(Request $request, $roomCode)
    {
        // Memastikan request datang dari sesi 'B'
        if (session('my_team_identifier') !== 'B') {
            return response()->json(['message' => 'Aksi tidak diizinkan. Akses dari link Tim B.'], 403);
        }

        $panelmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        $state = $this->handleTimeoutAndGetState($panelmatch);

        // Memastikan state di cache adalah giliran 'B'
        if ($state['turn'] !== 'B') {
            return response()->json(['message' => 'Bukan giliran Tim B.'], 403);
        }

        $mapName = $request->validate(['mapName' => 'required|string'])['mapName'];
        $state = $this->processAction($state, $mapName);

        Cache::put($this->getCacheKey($panelmatch), $state, now()->addHours(2));
        
        if($state['phase'] === 'end') {
            $this->savePicksToDb($panelmatch, $state['team_a_pick'], $state['team_b_pick']);
        }

        return response()->json($state);
    }

    private function processAction($state, $mapName)
    {
        if ($state['phase'] === 'ban') {
            $state['bans'][] = $mapName;
            if ($state['turn'] === 'A') {
                $state['team_a_bans'][] = $mapName;
            } else {
                $state['team_b_bans'][] = $mapName;
            }
            
            $state['turn'] = ($state['turn'] === 'A') ? 'B' : 'A';
            
            if (count($state['bans']) >= 6) {
                $state['phase'] = 'pick'; // FIX: BARIS INI YANG HILANG
                $state['turn'] = 'A';     // Reset giliran ke Tim A untuk memulai pick
            }

        } else if ($state['phase'] === 'pick') {
            $state['picks'][] = $mapName;
            if ($state['turn'] === 'A') {
                $state['team_a_pick'] = $mapName;
            } else {
                $state['team_b_pick'] = $mapName;
            }
            
            $state['turn'] = ($state['turn'] === 'A') ? 'B' : 'A';

            // Cek apakah fase pick sudah selesai
            if (count($state['picks']) >= 2) {
                $state['phase'] = 'end';
            }
        }

        // Reset timer untuk giliran berikutnya
        $state['turn_starts_at'] = now()->timestamp;
        $state['last_update'] = now()->timestamp;
        return $state;
    }

    private function performRandomAction($state)
    {
        $bannedOrPickedMaps = array_merge($state['bans'], $state['picks']);
        $availableToSelect = array_diff($this->availableMaps, $bannedOrPickedMaps);
        
        // ===== PERBAIKAN DI SINI =====
        // Jika tidak ada lagi map yang bisa dipilih, jangan lakukan apa-apa.
        if (empty($availableToSelect)) {
            return $state;
        }
        // ===== AKHIR PERBAIKAN =====

        $randomMap = array_values($availableToSelect)[array_rand(array_values($availableToSelect))];

        return $this->processAction($state, $randomMap);
    }

    private function savePicksToDb(Battle $battle, $mapA, $mapB)
    {
        $battle->update(['map_a' => $mapA, 'map_b' => $mapB]);
    }

    public function clearCache($roomCode)
    {
        $panelmatch = Battle::where('room_code', $roomCode)->firstOrFail();
        $cacheKey = $this->getCacheKey($panelmatch);

        Cache::forget($cacheKey);

        return response()->json(['status' => 'success', 'message' => 'Cache has been cleared.']);
    }

    private function getTimerCacheKey(Battle $battle)
    {
        return 'battle_' . $battle->id . '_timer_state';
    }

    public function controlTimer(Request $request)
    {
        $data = $request->validate([
            'battle_id' => 'required|exists:battles,id',
            'command'   => 'required|in:start,pause,reset',
            'minutes'   => 'required|integer|min:0',
            'seconds'   => 'required|integer|min:0|max:59',
        ]);

        $battle = Battle::findOrFail($data['battle_id']);
        $cacheKey = $this->getTimerCacheKey($battle);
        
        $state = Cache::get($cacheKey, [
            'status' => 'stopped', 'duration' => 600,
            'remaining_seconds_on_pause' => 600,
        ]);

        $totalSeconds = ($data['minutes'] * 60) + $data['seconds'];

        if ($data['command'] === 'start') {
            $state['status'] = 'running';
            $state['duration'] = ($state['status'] === 'paused' && isset($state['remaining_seconds_on_pause']))
                                 ? $state['remaining_seconds_on_pause']
                                 : $totalSeconds;
            $state['started_at'] = now()->timestamp;
        } 
        else if ($data['command'] === 'pause') {
            if ($state['status'] === 'running') {
                $state['status'] = 'paused';
                $elapsed = now()->timestamp - $state['started_at'];
                $state['remaining_seconds_on_pause'] = max(0, $state['duration'] - $elapsed);
            }
        } 
        else if ($data['command'] === 'reset') {
            $state['status'] = 'stopped';
            $state['duration'] = $totalSeconds;
            $state['remaining_seconds_on_pause'] = $totalSeconds;
        }

        $state['last_update'] = now()->timestamp;
        Cache::put($cacheKey, $state, now()->addHours(2));

        return response()->json(['success' => true, 'message' => 'Timer state updated!']);
    }
    
    public function getTimerState($roomCode)
    {
        $battle = Battle::where('room_code', $roomCode)->firstOrFail();
        $cacheKey = $this->getTimerCacheKey($battle);

        $state = Cache::get($cacheKey, [
            'status' => 'stopped',
            'duration' => 600,
            'last_update' => now()->timestamp
        ]);

        return response()->json($state);
    }
}