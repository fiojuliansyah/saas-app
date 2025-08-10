<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Veto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-red: #FD023B;
            --main-green: #0FF796;
            --main-yellow: #FFD700;
        }
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Russo One', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .font-teko { font-family: 'Teko', sans-serif; }
        .background-video-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2;
        }
        #bg-video {
            width: 100%; height: 100%; object-fit: cover;
            filter: blur(5px) brightness(0.3);
        }
        main {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-header {
            position: relative;
            height: 85px; /* Sedikit lebih tinggi */
            display: flex;
            align-items: center;
            z-index: 10;
        }
        .header-layer {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
        }
        /* Lapisan Latar Utama */
        .header-bg-main {
            background: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(8px);
            clip-path: polygon(0 0, 100% 0, 100% 75%, 97% 100%, 3% 100%, 0 75%);
        }
        /* Lapisan Aksen Garis Merah */
        .header-bg-accent {
            background: var(--main-green);
            clip-path: polygon(3% 100%, 97% 100%, 97.5% calc(100% - 2px), 3.5% calc(100% - 2px));
        }
        .header-content {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .event-logo-container {
            height: 100px; /* Lebih besar dari header, jadi menonjol */
            width: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .event-logo { height: 60px; }
        .event-title-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            line-height: 1;
        }
        .event-title-main {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            letter-spacing: 2px;
            font-weight: 700;
        }
        .event-title-year {
            font-size: 1.2rem;
            color: var(--main-green);
            font-weight: 700;
            letter-spacing: 5px;
            text-transform: uppercase;
        }
        .header-details { text-align: right; }
        .live-indicator { color: var(--main-red); font-weight: bold; animation: pulse 1.5s infinite; }


        /* Footer Styling */
        .page-footer { width: 100%; flex-shrink: 0; z-index: 10; padding: 0.5rem 0; background-color: var(--main-green); overflow: hidden; white-space: nowrap; }
        .marquee { display: inline-block; animation: marquee 25s linear infinite; text-transform: uppercase; }
        .marquee span { margin: 0 2rem; }
        
        /* MAP VETO STYLING (Tidak diubah) */
        .team-logo-container { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; transition: transform 0.3s ease, filter 0.3s ease; filter: brightness(0.7); }
        .team-logo-container.active { transform: scale(1.1); filter: brightness(1.2); }
        .team-veto-logo { height: 300px; }
        .team-veto-name { font-family: 'Teko', sans-serif; font-size: 2.5rem; }
        .vs-text { font-family: 'Teko', sans-serif; font-size: 2rem; color: var(--main-green); margin-bottom: 1rem; }
        .map-pool { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; max-width: 680px; }
        .map-card { width: 150px; height: 90px; border: 2px solid rgba(255, 255, 255, 0.2); position: relative; background-size: cover; background-position: center; transition: all 0.4s ease; overflow: hidden; }
        .map-card::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); transition: background 0.4s ease; }
        .map-name { position: absolute; bottom: 5px; left: 10px; z-index: 2; font-size: 1.1rem; text-transform: uppercase; transition: color 0.4s ease, text-shadow 0.4s ease; }
        .map-overlay { position: absolute; inset: 0; display: flex; justify-content: center; align-items: center; font-family: 'Teko', sans-serif; font-size: 1.8rem; letter-spacing: 2px; font-weight: 700; opacity: 0; transform: scale(1.2); transition: all 0.4s ease; }
        .map-card.banned { filter: grayscale(1) brightness(0.5); transform: scale(0.95); }
        .map-card.banned .map-overlay { background-color: rgba(253, 2, 59, 0.7); opacity: 1; transform: scale(1); }
        .map-card.picked .map-overlay { background-color: rgba(5, 255, 130, 0.7); opacity: 1; transform: scale(1); }
        .map-card.decider { border-color: var(--main-yellow); transform: scale(1.1); box-shadow: 0 0 20px var(--main-yellow); }
        .map-card.decider .map-overlay { background-color: rgba(255, 215, 0, 0.7); opacity: 1; transform: scale(1); color: #000; }
        .map-card.banned .map-name { color: var(--main-red); text-shadow: 0 0 6px var(--main-red); }
        .map-card.picked .map-name { color: var(--main-green); text-shadow: 0 0 6px var(--main-green); }
        .map-card.decider .map-name { color: var(--main-yellow); text-shadow: 0 0 6px var(--main-yellow); }
        .map-card.decider::after, .map-card.picked::after { background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.4)); }
        .veto-status { margin-top: 1.5rem; height: 50px; font-family: 'Teko', sans-serif; font-size: 2.5rem; text-align: center; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.3s ease; }

        /* Animations */
        @keyframes pulse { 0%, 100% { opacity: 1; text-shadow: 0 0 8px var(--main-red); } 50% { opacity: 0.6; text-shadow: none; } }
        @keyframes marquee { from { transform: translateX(0%); } to { transform: translateX(-50%); } }
        @keyframes slideInDown { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes slideInUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-on-load { opacity: 0; animation-duration: 0.7s; animation-fill-mode: forwards; animation-timing-function: ease-out; }

    </style>
</head>
<body>
    <div class="background-video-container">
        <video id="bg-video" autoplay loop muted playsinline>
            <source src="/huds/delta-force-v1/assets/videos/bg.mp4" type="video/mp4">
        </video>
    </div>

    <header class="page-header animate-on-load" style="animation-name: slideInDown;">
        <div class="header-layer header-bg-main"></div>
        <div class="header-layer header-bg-accent"></div>
        <div class="header-content">
            <div class="event-logo-container">
                <img src="/huds/delta-force-v1/assets/scrim.png" alt="Event Logo" class="event-logo">
            </div>
            <div class="event-title-container">
                <div class="event-title-main">SCRIM BATTLE</div>
                <div class="event-title-year">2025</div>
            </div>
            <div class="header-details">
                <div class="live-indicator">● LIVE</div>
                <div id="current-date" class="text-sm"></div>
            </div>
        </div>
    </header>

    <main>
        <div class="w-full max-w-7xl mx-auto flex items-center justify-around">
            <div class="team-logo-container" id="team-a-container">
                <img src="{{ asset('storage/' . $banmapmatch->teamA->logo) }}" alt="{{ $banmapmatch->teamA->name }} Logo" class="team-veto-logo">
                <div class="team-veto-name">{{ $banmapmatch->teamA->name }}</div>
            </div>
            <div class="text-center">
                 <div class="vs-text">MAP VETO</div>
                <div id="map-pool" class="map-pool"></div>
                <div id="veto-status" class="veto-status">Menunggu proses veto dimulai...</div>
            </div>
            <div class="team-logo-container" id="team-b-container">
                <img src="{{ asset('storage/' . $banmapmatch->teamB->logo) }}" alt="{{ $banmapmatch->teamB->name }} Logo" class="team-veto-logo">
                <div class="team-veto-name">{{ $banmapmatch->teamB->name }}</div>
            </div>
        </div>
    </main>
    
    <footer class="page-footer animate-on-load" style="animation-name: slideInUp;">
        <div class="marquee"><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>•
                GIHUB.GG •</span><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>•
                GIHUB.GG •</span><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>• GIHUB.GG •</span><span>•
                GIHUB.GG •</span></div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Inisialisasi Awal ---
        document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });

        const mapPoolContainer = document.getElementById('map-pool');
        const vetoStatusEl = document.getElementById('veto-status');
        const teamAContainer = document.getElementById('team-a-container');
        const teamBContainer = document.getElementById('team-b-container');
        
        // Mengambil data dari controller
        const roomCode = "{{ $roomCode }}";
        const teamAName = "{{ $banmapmatch->teamA->name }}";
        const teamBName = "{{ $banmapmatch->teamB->name }}";
        const getStateUrl = "{{ route('map.selection.get_state', $roomCode) }}";
        
        let localState = { last_update: 0 };
        let pollingInterval;

        // Definisikan map pool (ini bisa tetap statis)
        const maps = [
            { name: 'CyClone', image: '/huds/delta-force-v1/assets/maps/cyclone.png' },
            { name: 'Train Wreck', image: '/huds/delta-force-v1/assets/maps/train.png' },
            // { name: 'Knife Edge', image: '/huds/delta-force-v1/assets/maps/knife.png' },
            { name: 'Threshold', image: '/huds/delta-force-v1/assets/maps/threshold.png' },
            { name: 'Ascension', image: '/huds/delta-force-v1/assets/maps/ascension.png' },
            { name: 'Cracked', image: '/huds/delta-force-v1/assets/maps/cracked.png' },
            { name: 'Trench Lines', image: '/huds/delta-force-v1/assets/maps/trench-lines.png' },
            // { name: 'Shafted', image: '/huds/delta-force-v1/assets/maps/shafted.jpg' },
        ];

        // Buat kartu map di HTML
        maps.forEach(map => {
            mapPoolContainer.innerHTML += `
                <div class="map-card" data-map="${map.name}" style="background-image: url('${map.image}')">
                    <div class="map-name">${map.name}</div>
                    <div class="map-overlay"></div>
                </div>
            `;
        });
        
        // --- FUNGSI REAL-TIME ---

        // Fungsi utama untuk mengupdate seluruh UI berdasarkan state dari server
        function updateOverlayUI(state) {
            // 1. Reset semua status map & tim
            document.querySelectorAll('.map-card').forEach(card => {
                card.className = 'map-card'; // Reset ke class awal
                card.querySelector('.map-overlay').textContent = '';
            });
            teamAContainer.classList.remove('active');
            teamBContainer.classList.remove('active');

            // 2. Terapkan status BANNED
            state.bans.forEach(mapName => {
                const card = document.querySelector(`.map-card[data-map="${mapName}"]`);
                if (card) {
                    card.classList.add('banned');
                    card.querySelector('.map-overlay').textContent = 'BANNED';
                }
            });
            
            // 3. Terapkan status PICKED
            state.picks.forEach(mapName => {
                const card = document.querySelector(`.map-card[data-map="${mapName}"]`);
                if (card) {
                    card.classList.add('picked');
                    card.querySelector('.map-overlay').textContent = 'PICKED';
                }
            });

            // 4. Update status teks dan tim aktif
            const activeTeamName = state.turn === 'A' ? teamAName : teamBName;
            const activeTeamContainer = state.turn === 'A' ? teamAContainer : teamBContainer;

            if (state.phase === 'ban') {
                vetoStatusEl.innerHTML = `Team ${activeTeamName.toUpperCase()} sedang melakukan BAN...`;
                activeTeamContainer.classList.add('active');
            } else if (state.phase === 'pick') {
                vetoStatusEl.innerHTML = `Team ${activeTeamName.toUpperCase()} sedang melakukan PICK...`;
                activeTeamContainer.classList.add('active');
            } else if (state.phase === 'end') {
                let statusHTML = 'PETA PILIHAN <br>';
                const picksText = [];
                if(state.team_a_pick) picksText.push(`${teamAName.toUpperCase()}: <span style="color: var(--main-green); text-shadow: 0 0 6px var(--main-green);">${state.team_a_pick}</span>`);
                if(state.team_b_pick) picksText.push(`${teamBName.toUpperCase()}: <span style="color: var(--main-green); text-shadow: 0 0 6px var(--main-green);">${state.team_b_pick}</span>`);
                vetoStatusEl.innerHTML = statusHTML + picksText.join(' <br> ');
                if (pollingInterval) clearInterval(pollingInterval); // Hentikan polling jika selesai
            }
        }

        // Fungsi untuk polling ke server
        const pollServer = async () => {
            try {
                const response = await fetch(getStateUrl);
                if (!response.ok) throw new Error(`Server responded with ${response.status}`);
                
                const serverState = await response.json();
                
                // Update UI hanya jika ada perubahan
                if (serverState && serverState.last_update > localState.last_update) {
                    localState = serverState;
                    updateOverlayUI(localState);
                }
            } catch (error) {
                console.error('Polling error:', error);
                if (pollingInterval) clearInterval(pollingInterval); // Hentikan jika error
            }
        };

        // Jalankan polling
        pollServer(); // Panggil sekali di awal
        pollingInterval = setInterval(pollServer, 2000); // Ulangi setiap 2 detik

    });
    </script>
</body>
</html>