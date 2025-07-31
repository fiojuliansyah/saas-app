<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HudController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\OverlayController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/teams/import', [TeamController::class, 'import'])->name('teams.import.store');
    Route::resource('teams', TeamController::class);

    Route::post('/players/import', [PlayerController::class, 'import'])->name('players.import.store');
    Route::resource('players', PlayerController::class);

    Route::resource('matches', BattleController::class);

    Route::get('formats/{id?}', [FormatController::class, 'createOrEdit'])->name('formats.createOrEdit');
    Route::post('formats/{id?}', [FormatController::class, 'updateOrCreate'])->name('formats.updateOrCreate');

    Route::get('/panels', [PanelController::class, 'index'])->name('panels.index');
    Route::get('/panels/hud/{roomCode}', [PanelController::class, 'panelController'])->name('panels.controller');
    Route::post('/panel/update-score', [PanelController::class, 'updateScore'])->name('panel.updateScore');
    Route::post('/panel/swap-sides', [PanelController::class, 'swapSides'])->name('panel.swapSides');
    Route::post('/panel/update-title', [PanelController::class, 'updateTitle'])->name('panel.updateTitle');
    Route::post('/panel/control-timer', [PanelController::class, 'controlTimer'])->name('panel.controlTimer');
    Route::get('/panel/{roomCode}/get-timer-state', [PanelController::class, 'getTimerState'])->name('panel.getTimerState');
    
    Route::post('/map-selection/{roomCode}/clear-cache', [PanelController::class, 'clearCache'])->name('map.selection.clear_cache'); 
    Route::post('/panel/{roomCode}/start-banpick', [PanelController::class, 'startBanPick'])->name('panel.startBanPick');
});

    Route::get('/map-selection/{roomCode}/team/{teamIdentifier}', [PanelController::class, 'showPickMapPanel'])->name('map.selection.show_for_team');
    Route::get('/map-selection/{roomCode}/get-state', [PanelController::class, 'getState'])->name('map.selection.get_state');
    Route::post('/map-selection/{roomCode}/team-a/action', [PanelController::class, 'handleTeamAAction'])->name('map.selection.team_a_action');
    Route::post('/map-selection/{roomCode}/team-b/action', [PanelController::class, 'handleTeamBAction'])->name('map.selection.team_b_action');
    Route::post('/map-selection/{roomCode}/save-picks', [PanelController::class, 'savePicks'])->name('map.selection.save');
    Route::get('/overlay/{room_code}/data', [OverlayController::class, 'getOverlayData'])->name('overlay.data');
    Route::get('/overlay/{roomCode}/cover', [OverlayController::class, 'cover'])->name('overlay.cover');
    Route::get('/overlay/{roomCode}/topbar', [OverlayController::class, 'topbar'])->name('overlay.topbar');
    Route::get('/overlay/{roomCode}/swap-sides', [OverlayController::class, 'swapsides'])->name('overlay.swapsides');
    Route::get('/overlay/{roomCode}/ban-maps', [OverlayController::class, 'banmaps'])->name('overlay.banmaps');
    Route::get('/overlay/{roomCode}/timeout', [OverlayController::class, 'timeout'])->name('overlay.timeout');
    Route::get('/overlay/transition-v1', [OverlayController::class, 'transitionV1'])->name('overlay.transition-v1');
    Route::get('/overlay/transition-v2', [OverlayController::class, 'transitionV2'])->name('overlay.transition-v2');
    Route::get('/overlay/replay', [OverlayController::class, 'replay'])->name('overlay.replay');
    Route::get('/overlay/get-swap-data/{roomCode}', [OverlayController::class, 'getSwapData'])->name('overlay.swap.data');

require __DIR__.'/auth.php';
