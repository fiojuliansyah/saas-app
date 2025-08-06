<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Map Ban & Pick - {{ $panelmatch->teamA->name }} vs {{ $panelmatch->teamB->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Teko', sans-serif; background-color: #111827; }
        .team-panel.active { box-shadow: 0 0 25px rgba(234, 179, 8, 0.6); }
        .map-card { position: relative; aspect-ratio: 16/9; border-radius: 0.5rem; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.3s ease; }
        .map-card:not(.is-banned):not(.is-picked):hover { border-color: #fde047; }
        .map-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
        .group:hover .map-image { transform: scale(1.1); }
        .map-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); }
        .map-name { position: absolute; bottom: 0; left: 0; padding: 0.75rem; font-size: 1.5rem; font-weight: bold; }
        .map-action-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .group:hover .map-action-overlay { opacity: 1; }
        .action-button { padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: bold; font-size: 1.25rem; }
        .map-banned-overlay, .map-picked-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: white; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        .map-card.is-banned { filter: grayscale(1) brightness(0.5); cursor: not-allowed; }
        .map-card.is-banned .map-banned-overlay { background: rgba(153, 27, 27, 0.7); opacity: 1; }
        .map-card.is-picked { filter: grayscale(0) brightness(0.8); cursor: not-allowed; }
        .map-card.is-picked .map-picked-overlay { background: rgba(21, 128, 61, 0.7); opacity: 1; }
        .map-card.is-banned .map-action-overlay, .map-card.is-picked .map-action-overlay { display: none; }
        .disabled-pool { pointer-events: none; filter: grayscale(0.7) opacity(0.5); }
    </style>
</head>
<body class="text-white p-4 sm:p-8 flex flex-col items-center min-h-screen">
    
    <header class="text-center mb-6 mt-8">
        <h1 id="phase-title" class="text-5xl md:text-6xl font-bold tracking-wider uppercase transition-colors duration-300 text-red-500">FASE BAN</h1>
        <p id="turn-indicator" class="text-2xl text-yellow-300 font-semibold mt-1">Waiting for server...</p>
        <p id="timer" class="text-4xl text-yellow-400 font-bold">30</p>
    </header>

    <main class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div id="team-a-panel" class="team-panel lg:col-span-1 bg-gray-800 border-2 border-red-500 rounded-lg p-4 text-center transition-all duration-300">
            <h2 class="text-3xl font-bold uppercase text-red-500">{{ $panelmatch->teamA->name }}</h2>
            <img src="{{ asset('storage/' . $panelmatch->teamA->logo) ?? 'https://via.placeholder.com/100' }}" alt="Logo Team A" class="mx-auto my-4 h-24 w-24 object-contain">
            <div class="space-y-3">
                <div><p class="text-xl text-gray-400">MAP BAN:</p><div id="team-a-bans" class="mt-1 text-2xl font-bold text-red-400 min-h-[5rem] flex flex-col items-center justify-center"></div></div>
                <div class="border-t border-gray-700 my-2"></div>
                <div><p class="text-xl text-gray-400">MAP CHOOSE:</p><div id="team-a-pick" class="mt-1 text-3xl font-bold text-green-400 min-h-[2.5rem] flex items-center justify-center"></div></div>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div id="map-pool" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="map-card group" data-map-name="CyClone"><img src="/huds/delta-force-v1/assets/maps/cyclone.png" alt="CyClone" class="map-image"><div class="map-overlay"></div><h3 class="map-name">CYCLONE</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Train Wreck"><img src="/huds/delta-force-v1/assets/maps/train.png" alt="Train Wreck" class="map-image"><div class="map-overlay"></div><h3 class="map-name">TRAIN WRECK</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Knife Edge"><img src="/huds/delta-force-v1/assets/maps/knife.png" alt="Knife Edge" class="map-image"><div class="map-overlay"></div><h3 class="map-name">KNIFE EDGE</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Threshold"><img src="/huds/delta-force-v1/assets/maps/threshold.png" alt="Threshold" class="map-image"><div class="map-overlay"></div><h3 class="map-name">THRESHOLD</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Ascension"><img src="/huds/delta-force-v1/assets/maps/ascension.png" alt="Ascension" class="map-image"><div class="map-overlay"></div><h3 class="map-name">ASCENSION</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Cracked"><img src="/huds/delta-force-v1/assets/maps/cracked.png" alt="Cracked" class="map-image"><div class="map-overlay"></div><h3 class="map-name">CRACKED</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Trench Lines"><img src="/huds/delta-force-v1/assets/maps/trench-lines.png" alt="Trench Lines" class="map-image"><div class="map-overlay"></div><h3 class="map-name">TRENCH LINES</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
                <div class="map-card group" data-map-name="Shafted"><img src="/huds/delta-force-v1/assets/maps/shafted.jpg" alt="Shafted" class="map-image"><div class="map-overlay"></div><h3 class="map-name">SHAFTED</h3><div class="map-action-overlay"><button class="action-button bg-red-600 hover:bg-red-500">BAN MAP</button></div><div class="map-banned-overlay"><span>BANNED</span></div><div class="map-picked-overlay"><span>PICKED</span></div></div>
            </div>
        </div>

        <div id="team-b-panel" class="team-panel lg:col-span-1 bg-gray-800 border-2 border-blue-500 rounded-lg p-4 text-center transition-all duration-300">
            <h2 class="text-3xl font-bold uppercase text-blue-500">{{ $panelmatch->teamB->name }}</h2>
            <img src="{{ asset('storage/' . $panelmatch->teamB->logo) ?? 'https://via.placeholder.com/100' }}" alt="Logo Team B" class="mx-auto my-4 h-24 w-24 object-contain">
            <div class="space-y-3">
                <div><p class="text-xl text-gray-400">MAP BAN:</p><div id="team-b-bans" class="mt-1 text-2xl font-bold text-red-400 min-h-[5rem] flex flex-col items-center justify-center"></div></div>
                <div class="border-t border-gray-700 my-2"></div>
                <div><p class="text-xl text-gray-400">MAP CHOOSE:</p><div id="team-b-pick" class="mt-1 text-3xl font-bold text-green-400 min-h-[2.5rem] flex items-center justify-center"></div></div>
            </div>
        </div>
    </main>

    <footer class="w-full max-w-4xl mt-auto pt-8 pb-4">
        <h3 class="text-center text-2xl text-gray-400 font-bold tracking-widest mb-4">SPONSORED BY</h3>
        <div class="flex justify-center items-center gap-x-8 md:gap-x-12">
            <img src="/huds/delta-force-v1/assets/gihud.png" alt="Sponsor 1" class="max-h-16 transition-transform hover:scale-110 filter grayscale brightness-0 invert">
            <img src="/huds/delta-force-v1/assets/delta-force.png" alt="Sponsor 2" class="max-h-16 transition-transform hover:scale-110 filter grayscale brightness-0 invert">
            <img src="/huds/delta-force-v1/assets/dunia-games.png" alt="Sponsor 3" class="max-h-16 transition-transform hover:scale-110 filter grayscale brightness-0 invert">
        </div>
    </footer>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    let localState = { last_update: 0 };
    let pollingInterval;
    let visualTimerInterval;

    const timerElement = document.getElementById('timer');
    const phaseTitle = document.getElementById('phase-title');
    const turnIndicator = document.getElementById('turn-indicator');
    const teamAPanel = document.getElementById('team-a-panel');
    const teamBPanel = document.getElementById('team-b-panel');
    const teamABans = document.getElementById('team-a-bans');
    const teamBBans = document.getElementById('team-b-bans');
    const teamAPick = document.getElementById('team-a-pick');
    const teamBPick = document.getElementById('team-b-pick');
    const mapCards = document.querySelectorAll('.map-card');
    const mapPool = document.getElementById('map-pool');
    
    const myTeam = "{{ $myTeam ?? '' }}";
    const teamAName = "{{ $panelmatch->teamA->name }}";
    const teamBName = "{{ $panelmatch->teamB->name }}";
    const getStateUrl = "{{ route('map.selection.get_state', $panelmatch->room_code) }}";
    const teamAActionUrl = "{{ route('map.selection.team_a_action', $panelmatch->room_code) }}";
    const teamBActionUrl = "{{ route('map.selection.team_b_action', $panelmatch->room_code) }}";

    const updateVisualTimer = (turnStartTime) => {
        if (visualTimerInterval) clearInterval(visualTimerInterval);
        timerElement.style.display = 'block';

        visualTimerInterval = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - turnStartTime;
            const remaining = 30 - elapsed;
            
            if (remaining >= 0) {
                timerElement.textContent = remaining;
            } else {
                timerElement.textContent = "0";
                clearInterval(visualTimerInterval);
            }
        }, 500);
    };

    const updateUI = (state) => {
        if (state.phase === 'waiting') {
            phaseTitle.textContent = 'WAITING OPERATOR';
            phaseTitle.className = "text-5xl md:text-6xl font-bold tracking-wider uppercase transition-colors duration-300 text-gray-400";
            turnIndicator.textContent = 'The ban/pick process will begin soon...';
            timerElement.style.display = 'none';
            mapPool.classList.add('disabled-pool');
            teamAPanel.classList.remove('active');
            teamBPanel.classList.remove('active');
            return;
        }

        mapCards.forEach(card => card.classList.remove('is-banned', 'is-picked'));
        
        teamABans.innerHTML = '';
        teamBBans.innerHTML = '';
        
        state.team_a_bans.forEach(mapName => {
            const card = document.querySelector(`.map-card[data-map-name="${mapName}"]`);
            if (card) card.classList.add('is-banned');
            teamABans.innerHTML += `<div>${mapName}</div>`;
        });
        state.team_b_bans.forEach(mapName => {
            const card = document.querySelector(`.map-card[data-map-name="${mapName}"]`);
            if (card) card.classList.add('is-banned');
            teamBBans.innerHTML += `<div>${mapName}</div>`;
        });

        teamAPick.textContent = state.team_a_pick || '';
        if (state.team_a_pick) {
            const card = document.querySelector(`.map-card[data-map-name="${state.team_a_pick}"]`);
            if (card) card.classList.add('is-picked');
        }
        teamBPick.textContent = state.team_b_pick || '';
        if (state.team_b_pick) {
            const card = document.querySelector(`.map-card[data-map-name="${state.team_b_pick}"]`);
            if (card) card.classList.add('is-picked');
        }

        if (state.phase === 'end') {
            phaseTitle.textContent = "SELECTION COMPLETED";
            phaseTitle.className = "text-5xl md:text-6xl font-bold tracking-wider uppercase transition-colors duration-300 text-yellow-400";
            turnIndicator.textContent = "Map telah ditentukan!";
            if (visualTimerInterval) clearInterval(visualTimerInterval);
            timerElement.style.display = 'none';
            if (pollingInterval) clearInterval(pollingInterval);
            mapPool.classList.remove('disabled-pool');
            return;
        }

        const isPickPhase = state.phase === 'pick';
        phaseTitle.textContent = isPickPhase ? "FASE PICK" : "FASE BAN";
        phaseTitle.className = `text-5xl md:text-6xl font-bold tracking-wider uppercase transition-colors duration-300 ${isPickPhase ? 'text-green-500' : 'text-red-500'}`;
        
        document.querySelectorAll('.action-button').forEach(button => {
            button.textContent = isPickPhase ? "CHOOSE MAP" : "BAN MAP";
            const buttonClasses = isPickPhase ? 'bg-green-600 hover:bg-green-500' : 'bg-red-600 hover:bg-red-500';
            button.className = `action-button ${buttonClasses}`;
        });

        const teamName = state.turn === 'A' ? teamAName : teamBName;
        const action = state.phase === 'ban' ? 'BAN' : 'PICK';
        turnIndicator.textContent = `${teamName} turn for ${action}`;
        teamAPanel.classList.toggle('active', state.turn === 'A');
        teamBPanel.classList.toggle('active', state.turn === 'B');
        updateVisualTimer(state.turn_starts_at);

        if (myTeam && state.phase !== 'end') {
            if (state.turn !== myTeam) {
                mapPool.classList.add('disabled-pool');
            } else {
                mapPool.classList.remove('disabled-pool');
            }
        }
    };

    const pollServer = async () => {
        try {
            const response = await fetch(getStateUrl);
            if (!response.ok) throw new Error(`Server responded with ${response.status}`);
            const serverState = await response.json();
            if (serverState && serverState.last_update > localState.last_update) {
                localState = serverState;
                updateUI(serverState);
            }
        } catch (error) {
            console.error('Polling error:', error);
            if (pollingInterval) clearInterval(pollingInterval);
        }
    };

    const sendAction = async (mapName) => {
        const currentTurn = localState.turn;
        if (!currentTurn) {
            alert("Please wait a moment, synchronizing data with the server...");
            return;
        }
        if (myTeam !== currentTurn) {
            alert(`Can't choose, now it's the opposing team's turn!`);
            return;
        }
        const actionUrl = (currentTurn === 'A') ? teamAActionUrl : teamBActionUrl;
        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mapName: mapName })
            });
            const newState = await response.json();
            if (response.ok) {
                localState = newState;
                updateUI(newState);
            } else {
                alert(`Action Failed: ${newState.message}`);
            }
        } catch (error) {
            console.error('Failed to send action:', error);
        }
    };

    mapCards.forEach(card => {
        card.addEventListener('click', () => {
            if (card.classList.contains('is-banned') || card.classList.contains('is-picked') || (localState && localState.phase === 'end')) {
                return;
            }
            const mapName = card.dataset.mapName;
            sendAction(mapName);
        });
    });

    pollServer();
    pollingInterval = setInterval(pollServer, 2000);
});
</script>
</body>
</html>