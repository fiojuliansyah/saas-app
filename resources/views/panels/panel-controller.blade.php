<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- WAJIB: Tambahkan CSRF Token untuk keamanan AJAX di Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Competition HUD Controller (Interactive)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Style untuk notifikasi */
        #notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            transition: opacity 0.5s, transform 0.5s;
            z-index: 100;
        }
    </style>
</head>
<!-- Tambahkan ID battle ke body agar mudah diakses JS -->
<body class="bg-gray-900 text-white p-4 sm:p-6 lg:p-8" data-battle-id="{{ $panelmatch->id }}">

    <!-- Container Notifikasi -->
    <div id="notification" class="hidden bg-green-500 text-white py-2 px-5 rounded-lg shadow-lg">
        <p id="notification-message">Teks berhasil disalin!</p>
    </div>

    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center uppercase tracking-wider">HUD CONTROLLER PANEL</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- KONTROL TIM 1 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-red-500">TEAM 1 CONTROLS</h3>
                    <button class="minimize-btn text-gray-400 hover:text-white transition">
                        <svg class="icon-minus h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        <svg class="icon-plus h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>
                <div id="team-a-display" class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg">
                    <img src="{{ asset('assets/images/flags/' . ($panelmatch->teamA->country ?? 'default') . '.png') }}" alt="Country Flag" class="h-6 object-cover rounded-sm">
                    <img src="{{ asset('storage/' . $panelmatch->teamA->logo) ?? 'https://via.placeholder.com/40' }}" alt="Team Logo" class="h-10 w-10 object-contain">
                    <span class="text-lg font-bold text-white flex-grow">{{ $panelmatch->teamA->name ?? 'Nama Tim 1' }}</span>
                </div>
                <div>
                    <label for="team-a-score" class="block text-sm font-medium text-gray-400 mb-1">Score</label>
                    <div class="flex items-center gap-4">
                        <!-- PERBAIKAN: Tambahkan class="score-btn" dan data-attributes -->
                        <button data-team="a" data-action="decrement" class="score-btn bg-red-600 hover:bg-red-700 text-white font-bold p-3 rounded-md transition duration-300">-</button>
                        <!-- PERBAIKAN: Ubah ID agar konsisten -->
                        <input type="number" id="team-a-score" value="{{ $panelmatch->score_team_a ?? 0 }}" class="w-full bg-gray-700 text-center text-white text-lg rounded-md p-2">
                        <!-- PERBAIKAN: Tambahkan class="score-btn" dan data-attributes -->
                        <button data-team="a" data-action="increment" class="score-btn bg-green-600 hover:bg-green-700 text-white font-bold p-3 rounded-md transition duration-300">+</button>
                    </div>
                </div>
                <div>
                    <label for="link-banmap-oxygen" class="block text-sm font-medium text-gray-400 mb-1">{{ $panelmatch->teamA->name ?? 'Nama Tim A' }} Panel Pick Maps</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-banmap-oxygen" value="{{ route('map.selection.show_for_team', ['roomCode' => $panelmatch->room_code, 'teamIdentifier' => 'a']) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- KONTROL GLOBAL -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-center">MATCH CONTROLS</h3>
                    <button class="minimize-btn text-gray-400 hover:text-white transition">
                        <svg class="icon-minus h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        <svg class="icon-plus h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>
                <div>
                    <label for="match-title" class="block text-sm font-medium text-gray-400 mb-1">Match Title</label>
                    <input type="text" id="match-title" value="{{ $panelmatch->panel->title ?? 'Grand Final' }}" class="w-full bg-gray-700 text-white border-gray-600 rounded-md p-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1 text-center">Timeout Timer (MM:SS)</label>
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <input type="number" id="timer-minutes" value="10" class="w-1/2 bg-gray-700 text-center text-white text-lg rounded-md p-2">
                        <span class="font-bold">:</span>
                        <input type="number" id="timer-seconds" value="00" class="w-1/2 bg-gray-700 text-center text-white text-lg rounded-md p-2">
                    </div>
                    <div class="flex justify-between gap-2">
                        <button id="timer-start" class="timer-btn w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 p-2 rounded-md transition duration-300">Start</button>
                        <button id="timer-pause" class="timer-btn w-full flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 p-2 rounded-md transition duration-300">Pause</button>
                        <button id="timer-reset" class="timer-btn w-full flex items-center justify-center gap-2 bg-red-700 hover:bg-red-800 p-2 rounded-md transition duration-300">Reset</button>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-4">
                    <button id="swap-sides-btn" class="w-full flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 12l-4-4m4 4l4-4m6 8v-12m0 12l-4-4m4 4l4-4" /></svg>
                        Swap Sides
                    </button>
                </div>
                <div class="mt-4 border-t border-gray-700 pt-4 flex gap-2">
                    <button id="start-banpick-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                        Start Ban/Pick
                    </button>
                    <button id="reset-banpick-btn" class="w-full bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition duration-300">
                        Reset Ban/Pick
                    </button>
                </div>
            </div>

            <!-- KONTROL TIM 2 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4">
                <div class="flex justify-between items-center">
                     <h3 class="text-xl font-bold text-blue-500">TEAM 2 CONTROLS</h3>
                      <button class="minimize-btn text-gray-400 hover:text-white transition">
                        <svg class="icon-minus h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        <svg class="icon-plus h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>
                <div id="team-b-display" class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg">
                    <img src="{{ asset('assets/images/flags/' . ($panelmatch->teamB->country ?? 'default') . '.png') }}" alt="Country Flag" class="h-6 object-cover rounded-sm">
                    <img src="{{ asset('storage/' . $panelmatch->teamB->logo) ?? 'https://via.placeholder.com/40' }}" alt="Team Logo" class="h-10 w-10 object-contain">
                    <span class="text-lg font-bold text-white flex-grow">{{ $panelmatch->teamB->name ?? 'Nama Tim 2' }}</span>
                </div>
                <div>
                    <label for="team-b-score" class="block text-sm font-medium text-gray-400 mb-1">Score</label>
                    <div class="flex items-center gap-4">
                        <button data-team="b" data-action="decrement" class="score-btn bg-red-600 hover:bg-red-700 text-white font-bold p-3 rounded-md transition duration-300">-</button>
                        <input type="number" id="team-b-score" value="{{ $panelmatch->score_team_b ?? 0 }}" class="w-full bg-gray-700 text-center text-white text-lg rounded-md p-2">
                        <button data-team="b" data-action="increment" class="score-btn bg-green-600 hover:bg-green-700 text-white font-bold p-3 rounded-md transition duration-300">+</button>
                    </div>
                </div>
                <div>
                    <label for="link-banmap-oxygen" class="block text-sm font-medium text-gray-400 mb-1">{{ $panelmatch->teamB->name ?? 'Nama Tim B' }} Panel Pick Maps</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-banmap-oxygen" value="{{ route('map.selection.show_for_team', ['roomCode' => $panelmatch->room_code, 'teamIdentifier' => 'b']) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <!-- BROADCAST LINKS -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-center text-teal-400">BROADCAST PAGE LINKS</h3>
                    <button class="minimize-btn text-gray-400 hover:text-white transition">
                        <svg class="icon-minus h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        <svg class="icon-plus h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>
                <div>
                    <label for="link-cover" class="block text-sm font-medium text-gray-400 mb-1">Cover</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-cover" value="{{ route('overlay.cover', $panelmatch->room_code) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-timeout" class="block text-sm font-medium text-gray-400 mb-1">Ban Maps</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-timeout" value="{{ route('overlay.banmaps', $panelmatch->room_code) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-timeout" class="block text-sm font-medium text-gray-400 mb-1">Timeout</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-timeout" value="{{ route('overlay.timeout', $panelmatch->room_code) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-timeout" class="block text-sm font-medium text-gray-400 mb-1">Replay</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-timeout" value="{{ route('overlay.replay') }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-center text-teal-400">COMPONENTS</h3>
                    <button class="minimize-btn text-gray-400 hover:text-white transition">
                        <svg class="icon-minus h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" /></svg>
                        <svg class="icon-plus h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </button>
                </div>
                <div>
                    <label for="link-topbar" class="block text-sm font-medium text-gray-400 mb-1">Topbar Overlay</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-topbar" value="{{ route('overlay.topbar', $panelmatch->room_code) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-timeout" class="block text-sm font-medium text-gray-400 mb-1">Swap Sides Alert</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-timeout" value="{{ route('overlay.swapsides', $panelmatch->room_code) }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-cover" class="block text-sm font-medium text-gray-400 mb-1">Transition v1</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-cover" value="{{ route('overlay.transition-v1') }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="link-cover" class="block text-sm font-medium text-gray-400 mb-1">Transition v2</label>
                    <div class="flex gap-2">
                        <input type="text" id="link-cover" value="{{ route('overlay.transition-v2') }}" readonly class="flex-grow bg-gray-900 text-gray-400 border-gray-700 rounded-md p-2 text-sm">
                        <button class="copy-link-btn bg-teal-600 hover:bg-teal-700 text-white font-bold p-2 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        
        // --- KONFIGURASI & ELEMEN GLOBAL ---
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const battleId = document.body.dataset.battleId;
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        let notificationTimeout;

        function showNotification(message, isError = false) {
            notificationMessage.textContent = message;
            notification.classList.remove('hidden', 'bg-green-500', 'bg-red-500');
            notification.classList.add(isError ? 'bg-red-500' : 'bg-green-500');
            
            clearTimeout(notificationTimeout);
            notificationTimeout = setTimeout(() => {
                notification.classList.add('hidden');
            }, 3000);
        }

        // --- FUNGSI UTAMA (AJAX HELPER) ---
        async function sendRequest(url, data) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ battle_id: battleId, ...data })
                });
                return await response.json();
            } catch (error) {
                console.error('Network or server error:', error);
                showNotification('Koneksi ke server gagal.', true);
                return { success: false, message: 'Network error' };
            }
        }

        // --- FUNGSI MINIMIZE CARD ---
        document.querySelectorAll('.minimize-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const btn = event.currentTarget;
                const header = btn.parentElement;
                let sibling = header.nextElementSibling;
                while (sibling) {
                    sibling.classList.toggle('hidden');
                    sibling = sibling.nextElementSibling;
                }
                btn.querySelector('.icon-minus').classList.toggle('hidden');
                btn.querySelector('.icon-plus').classList.toggle('hidden');
            });
        });

        // --- FUNGSI UPDATE SKOR ---
        document.querySelectorAll('.score-btn').forEach(button => {
            button.addEventListener('click', async (event) => {
                const btn = event.currentTarget;
                const team = btn.dataset.team;
                const action = btn.dataset.action;
                const scoreInput = document.getElementById(`team-${team}-score`);
                if (!scoreInput) return;
                
                let currentScore = parseInt(scoreInput.value, 10);
                currentScore = (action === 'increment') ? currentScore + 1 : Math.max(0, currentScore - 1);
                scoreInput.value = currentScore;

                const result = await sendRequest("{{ route('panel.updateScore') }}", { team, score: currentScore });
                if (result.success) {
                    showNotification('Skor berhasil diupdate!');
                } else {
                    showNotification('Gagal update skor.', true);
                }
            });
        });

        // --- SWAP SIDE ---
        document.getElementById('swap-sides-btn').addEventListener('click', async () => {
            if (!confirm('Apakah Anda yakin ingin menukar kedua tim? Aksi ini akan menukar skor juga.')) return;

            const result = await sendRequest("{{ route('panel.swapSides') }}", {});
            if (result.success) {
                showNotification('Tim berhasil ditukar! Halaman akan dimuat ulang.');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showNotification('Gagal menukar tim.', true);
            }
        });

        // --- FUNGSI COPY LINK ---
        document.querySelectorAll('.copy-link-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const input = event.currentTarget.previousElementSibling;
                input.select(); // Pilih teks di dalam input
                try {
                    // Coba metode clipboard modern
                    navigator.clipboard.writeText(input.value);
                    showNotification('Link berhasil disalin!');
                } catch (err) {
                    // Fallback untuk browser lama
                    document.execCommand('copy');
                    showNotification('Link berhasil disalin! (fallback)');
                }
            });
        });

        const resetBanPickBtn = document.getElementById('reset-banpick-btn');
            if (resetBanPickBtn) {
                resetBanPickBtn.addEventListener('click', async () => {
                    if (!confirm('Apakah Anda yakin ingin me-reset state Ban/Pick untuk match ini?')) {
                        return;
                    }

                    try {
                        // Kita buat fetch request khusus di sini karena tidak perlu mengirim body/data
                        const response = await fetch("{{ route('map.selection.clear_cache', $panelmatch->room_code) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Gunakan fungsi notifikasi yang sudah ada
                            showNotification(result.message || 'State Ban/Pick berhasil di-reset!');
                        } else {
                            showNotification(result.message || 'Gagal me-reset state.', true);
                        }
                    } catch (error) {
                        console.error('Error resetting ban/pick state:', error);
                        showNotification('Terjadi kesalahan koneksi.', true);
                    }
            });
        }

        const startBanPickBtn = document.getElementById('start-banpick-btn');
        if (startBanPickBtn) {
            startBanPickBtn.addEventListener('click', async () => {
                if (!confirm('Mulai proses Ban/Pick sekarang?')) return;

                try {
                    // Panggil route 'panel.startBanPick' yang sudah kita buat
                    const response = await fetch("{{ route('panel.startBanPick', $panelmatch->room_code) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        showNotification(result.message || 'Proses Ban/Pick telah dimulai!');
                    } else {
                        showNotification(result.message || 'Gagal memulai proses.', true);
                    }
                } catch (error) {
                    console.error('Error starting Ban/Pick:', error);
                    showNotification('Terjadi kesalahan koneksi.', true);
                }
            });
        }

        const matchTitleInput = document.getElementById('match-title');
        if (matchTitleInput) {
            matchTitleInput.addEventListener('blur', async () => {
                const result = await sendRequest("{{ route('panel.updateTitle') }}", { title: matchTitleInput.value });
                if (result.success) {
                    showNotification('Judul pertandingan berhasil diupdate!');
                } else {
                    showNotification('Gagal mengupdate judul.', true);
                }
            });
        }

        const timerMinutesInput = document.getElementById('timer-minutes');
        const timerSecondsInput = document.getElementById('timer-seconds');
        const getTimerStateUrl = "{{ route('panel.getTimerState', $panelmatch->room_code) }}";
        let localTimerState = { last_update: 0 };
        let panelCountdownInterval;

        function formatTime(seconds) {
            if (seconds < 0) seconds = 0;
            const min = Math.floor(seconds / 60);
            const sec = seconds % 60;
            return { minutes: min, seconds: sec };
        }

        function updatePanelTimerUI(state) {
            clearInterval(panelCountdownInterval);

            if (state.status === 'running') {
                const serverStartTime = state.started_at;
                const initialDuration = state.duration;
                
                const runCountdown = () => {
                    const nowInSeconds = Math.floor(Date.now() / 1000);
                    const elapsed = nowInSeconds - serverStartTime;
                    const remaining = initialDuration - elapsed;
                    
                    if (remaining >= 0) {
                        const time = formatTime(remaining);
                        timerMinutesInput.value = time.minutes;
                        timerSecondsInput.value = time.seconds.toString().padStart(2, '0');
                    } else {
                        clearInterval(panelCountdownInterval);
                    }
                };
                runCountdown();
                panelCountdownInterval = setInterval(runCountdown, 1000);
            } else if (state.status === 'paused') {
                const time = formatTime(state.remaining_seconds_on_pause);
                timerMinutesInput.value = time.minutes;
                timerSecondsInput.value = time.seconds.toString().padStart(2, '0');
            } else { // 'stopped'
                const time = formatTime(state.duration);
                timerMinutesInput.value = time.minutes;
                timerSecondsInput.value = time.seconds.toString().padStart(2, '0');
            }
        }

        const pollTimerState = async () => {
            try {
                const response = await fetch(getTimerStateUrl);
                const serverState = await response.json();
                if (serverState && serverState.last_update > localTimerState.last_update) {
                    localTimerState = serverState;
                    updatePanelTimerUI(localTimerState);
                }
            } catch (error) { console.error('Timer polling error:', error); }
        };
        
        document.querySelectorAll('.timer-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const command = button.id.replace('timer-', '');
                
                let minutes, seconds;

                if (command === 'reset') {
                    minutes = 0;
                    seconds = 0;
                } else {
                    minutes = parseInt(timerMinutesInput.value) || 0;
                    seconds = parseInt(timerSecondsInput.value) || 0;
                }

                const result = await sendRequest("{{ route('panel.controlTimer') }}", {
                    command,
                    minutes,
                    seconds
                });

                if (result.success) {
                    showNotification(`Timer command '${command.toUpperCase()}' terkirim!`);
                    pollTimerState(); 
                } else {
                    showNotification(`Gagal mengirim '${command}' command.`, true);
                }
            });
        });

        pollTimerState();
        setInterval(pollTimerState, 2000);

    });
</script>

</body>
</html>