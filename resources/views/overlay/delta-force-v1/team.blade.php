<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Roster - Grand Finals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --main-green: #0FF796;
            --main-red: #FD023B;
            --team-1-color: #00B2FF;
            /* Oxygen Blue */
            --team-2-color: #F0C74F;
            /* Oxygen Blue */
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

        .font-teko {
            font-family: 'Teko', sans-serif;
        }

        .background-video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        #bg-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(5px) brightness(0.3);
        }

        main {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0;
        }

        /* --- ✨ HEADER BARU (SAMA SEPERTI RESULT PAGE) ✨ --- */
        .page-header {
            position: relative;
            height: 80px;
            display: flex;
            align-items: center;
            z-index: 10;
            flex-shrink: 0;
        }

        .header-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(8px);
            clip-path: polygon(0 0, 100% 0, 100% 75%, 97% 100%, 3% 100%, 0 75%);
            border-bottom: 2px solid var(--main-green);
        }

        .header-content {
            position: relative;
            width: 100%;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .event-logo {
            height: 45px;
        }

        .event-title-group {
            line-height: 1;
        }

        .event-title {
            font-family: 'Teko', sans-serif;
            font-size: 2.2rem;
            letter-spacing: 1px;
        }

        .event-year {
            color: var(--main-green);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .header-details {
            text-align: right;
        }

        .live-indicator {
            color: var(--main-red);
            font-weight: bold;
            animation: pulse 1.5s infinite;
        }

        /* Footer Styling */
        .page-footer {
            width: 100%;
            flex-shrink: 0;
            z-index: 10;
            padding: 0.5rem 0;
            background-color: var(--main-green);
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee {
            display: inline-block;
            animation: marquee 25s linear infinite;
            text-transform: uppercase;
        }

        .marquee span {
            margin: 0 2rem;
        }

        /* Roster Styling (Tidak berubah) */
        .roster-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 1.5rem;
            width: 100%;
            max-width: 1400px;
        }

        .team-panel {
            flex: 1;
            background-color: rgba(15, 15, 15, 0.5);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .team-panel.left {
            border-left: 4px solid var(--team-1-color);
            clip-path: polygon(0 0, 100% 0, 97% 100%, 0 100%);
        }

        .team-panel.right {
            border-right: 4px solid var(--team-2-color);
            clip-path: polygon(3% 0, 100% 0, 100% 100%, 0 100%);
        }

        .team-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.75rem;
        }

        .team-header img {
            height: 40px;
        }

        .team-header .team-name {
            font-family: 'Teko', sans-serif;
            font-size: 2rem;
            text-transform: uppercase;
            line-height: 1;
        }

        .squads-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .squad-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
        }

        .squad-name {
            font-family: 'Teko', sans-serif;
            font-size: 1.2rem;
            color: #aaa;
            margin-bottom: 0.25rem;
        }

        .squad-card:last-child {
            grid-column: span 2 / span 2;
        }

        .roster-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .roster-table td {
            padding: 0.2rem 0.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .roster-table tr:last-child td {
            border-bottom: none;
        }

        .roster-table .ign {
            font-weight: bold;
            font-size: 0.85rem;
        }

        .roster-table .role {
            font-size: 0.7rem;
            color: #888;
            text-align: right;
        }

        .roster-table tr.backup .role {
            color: var(--main-green);
            font-weight: bold;
        }

        .vs-divider {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            align-self: center;
            color: var(--main-green);
            text-shadow: 0 0 15px var(--main-green);
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.4s forwards;
        }

        /* Animations */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        @keyframes marquee {
            from {
                transform: translateX(0%);
            }

            to {
                transform: translateX(-50%);
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="background-video-container"><video id="bg-video" autoplay loop muted playsinline>
            <source src="/huds/delta-force-v1/assets/videos/bg.mp4" type="video/mp4">
        </video></div>

    <header class="page-header" style="opacity:0; animation: slideInDown 0.8s ease-out forwards;">
        <div class="header-background"></div>
        <div class="header-content">
            <div class="event-info">
                <img src="/huds/delta-force-v1/assets/scrim.png" alt="Event Logo"
                    class="event-logo">
                <div class="event-title-group">
                    <div class="event-title">SCRIM BATTLE</div>
                    <div class="event-year">2025</div>
                </div>
            </div>
            <div class="header-details">
                <div class="live-indicator">● LIVE</div>
                <div id="current-date" class="text-sm"></div>
            </div>
        </div>
    </header>

    <main>
        <div class="roster-container">
            <div class="team-panel left" style="animation-delay: 0.2s;">
                <div class="team-header"><img src="{{ asset('storage/' . $teammatch->teamA->logo) }}" alt="Oxygen Logo">
                    <h2 class="team-name" style="color: var(--team-1-color);">{{ $teammatch->teamA->name }}</h2>
                </div>
                <div class="squads-container">
                    @foreach ($squadTeamA as $squadName => $playersInSquad)
                        <div class="squad-card">
                            <h4 class="squad-name">{{ $squadName }}</h4>
                            <table class="roster-table">
                                <tbody>
                                    @foreach ($playersInSquad as $player)
                                        <tr>
                                            <td class="ign">{{ $player->nickname }}</td>
                                            <td class="role">{{ $player->role }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="vs-divider">VS</div>
            <div class="team-panel right" style="animation-delay: 0.2s;">
                <div class="team-header" style="flex-direction: row-reverse;"><img src="{{ asset('storage/' . $teammatch->teamB->logo) }}"
                        alt="Delta Logo">
                    <h2 class="team-name" style="color: var(--team-2-color);">{{ $teammatch->teamB->name }}</h2>
                </div>
                <div class="squads-container">
                    @foreach ($squadTeamB as $squadName => $playersInSquad)
                        <div class="squad-card">
                            <h4 class="squad-name">{{ $squadName }}</h4>
                            <table class="roster-table">
                                <tbody>
                                    @foreach ($playersInSquad as $player)
                                        <tr>
                                            <td class="ign">{{ $player->nickname }}</td>
                                            <td class="role">{{ $player->role }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <footer class="page-footer animate-on-load" style="animation-name: slideInUp;">
        <div class="marquee flex items-center gap-8 overflow-hidden whitespace-nowrap">
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/gihud-sponsor.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/sponsor.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/garena.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/dfcid.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/gihud-sponsor.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/sponsor.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/garena.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
            <span class="inline-flex items-center"><img src="/huds/delta-force-v1/assets/dfcid.png" alt="Sponsor 1" class="max-h-10 filter grayscale brightness-0 invert"></span>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateElement = document.getElementById('current-date');
            if (dateElement) {
                dateElement.textContent = new Date().toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            }
        });
    </script>
</body>

</html>
