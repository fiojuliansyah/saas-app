<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrim Battle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-green: #0FF796;
            --team-1-color: #00B2FF;
            --team-2-color: #FD023B;
        }
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Russo One', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }
        .font-teko { font-family: 'Teko', sans-serif; }

        .background-video-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            filter: brightness(0.5);
        }
        #bg-video {
            width: 100%; height: 100%; object-fit: cover;
        }

        .matchup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            width: 100%;
            max-width: 1400px;
            z-index: 2;
        }

        .team-panel {
            flex: 1;
            padding: 2rem;
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(8px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transisi untuk hover */
        }
        .team-panel.left {
            border-left: 6px solid var(--team-1-color);
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);
            animation: slide-in-left 0.8s cubic-bezier(0.2, 1, 0.3, 1) 0.2s forwards;
        }
        .team-panel.right {
            border-right: 6px solid var(--team-2-color);
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0% 100%);
            animation: slide-in-right 0.8s cubic-bezier(0.2, 1, 0.3, 1) 0.2s forwards;
        }
        /* EFEK HOVER BARU */
        .team-panel.left:hover {
            transform: scale(1.02);
            box-shadow: 0 0 25px -5px var(--team-1-color);
        }
        .team-panel.right:hover {
            transform: scale(1.02);
            box-shadow: 0 0 25px -5px var(--team-2-color);
        }

        .team-logo {
            max-height: 250px;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 15px rgba(0,0,0,0.5));
            animation: pulse 3s ease-in-out infinite;
        }
        .team-name {
            font-family: 'Teko', sans-serif;
            font-size: 4rem;
            line-height: 1;
            text-transform: uppercase;
        }

        .center-column {
            flex-basis: 350px;
            flex-shrink: 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 450px; 
        }
        
        .scrim-logo-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            animation: fade-in-down 0.8s ease 0.6s forwards;
        }

        .scrim-logo {
            width: 200px;
            margin-bottom: 0.5rem;
        }

        .vs-text {
            font-family: 'Teko', sans-serif;
            font-size: 6rem;
            line-height: 1;
            opacity: 0;
            animation: pop-in 0.6s ease 0.8s forwards;
        }

        /* --- COUNTDOWN TIMER STYLE --- */
        .countdown-container {
            opacity: 0;
            animation: pop-in 0.6s ease 1.2s forwards;
            text-align: center;
        }
        #countdown {
            display: flex;
            justify-content: center;
            gap: 1rem;
            font-family: 'Teko', sans-serif;
            text-transform: uppercase;
        }
        #countdown > div {
            text-align: center;
        }
        #countdown span {
            display: block;
            font-size: 3rem;
            line-height: 1;
            color: var(--main-green);
            text-shadow: 0 0 10px var(--main-green);
        }
        #countdown p {
            font-size: 1rem;
            font-family: 'Russo One', sans-serif;
        }
        /* --- AKHIR COUNTDOWN STYLE --- */

        .sponsor-container {
            opacity: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            animation: fade-in-up 0.8s ease 0.6s forwards;
        }
        .sponsor-title {
            font-size: 1rem;
            color: #aaa;
            text-transform: uppercase;
        }
        .sponsor-logo {
            max-height: 60px;
            margin-top: 0.5rem;
        }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.03); } }
        @keyframes slide-in-left { from { opacity:0; transform: translateX(-50px); } to { opacity:1; transform: translateX(0); } }
        @keyframes slide-in-right { from { opacity:0; transform: translateX(50px); } to { opacity:1; transform: translateX(0); } }
        @keyframes fade-in-down { from { opacity:0; transform: translateY(-30px); } to { opacity:1; transform: translateY(0); } }
        @keyframes fade-in-up { from { opacity:0; transform: translateY(30px); } to { opacity:1; transform: translateY(0); } }
        @keyframes pop-in { from { opacity:0; transform: scale(0.5); } to { opacity:1; transform: scale(1); } }
    </style>
</head>
<body>
    <div class="background-video-container">
        <video id="bg-video" autoplay loop muted playsinline>
            <source src="/huds/delta-force-v1/assets/videos/bg.mp4" type="video/mp4">
        </video>
    </div>

    <div class="matchup-container">
        <div class="team-panel left">
            <img src="{{ asset('storage/' . $covermatch->teamA->logo) }}" alt="Oxygen Logo" class="team-logo">
            <p class="team-name" style="color: var(--team-1-color);">{{ $covermatch->teamA->name }}</p>
            <img src="{{ asset('assets/images/flags/' . ($covermatch->teamA->country ?? 'default') . '.png') }}" alt="Bendera Indonesia" class="h-10 mt-2">
        </div>

        <div class="center-column">
            <div class="scrim-logo-container">
                <img src="/huds/delta-force-v1/assets/scrim.png" alt="Scrim Logo" class="scrim-logo">
            </div>
            
            <h2 class="vs-text">{{ $covermatch->score_team_a }} &nbsp; VS &nbsp; {{ $covermatch->score_team_b }}</h2>

            <div class="countdown-container">
                <div id="countdown">
                    <div><span id="days">00</span><p>Hari</p></div>
                    <div><span id="hours">00</span><p>Jam</p></div>
                    <div><span id="minutes">00</span><p>Menit</p></div>
                    <div><span id="seconds">00</span><p>Detik</p></div>
                </div>
            </div>
            <div class="sponsor-container">
                <p class="sponsor-title">Didukung oleh:</p>
                <img src="/huds/delta-force-v1/assets/gihud.png" alt="Sponsor Logo" class="sponsor-logo">
            </div>
        </div>
        
        <div class="team-panel right">
            <img src="{{ asset('storage/' . $covermatch->teamB->logo) }}" alt="Sky Logo" class="team-logo">
            <p class="team-name" style="color: var(--team-2-color);">{{ $covermatch->teamB->name }}</p>
            <img src="{{ asset('assets/images/flags/' . ($covermatch->teamB->country ?? 'default') . '.png') }}" alt="Bendera Malaysia" class="h-10 mt-2">
        </div>
    </div>

    <script>
        const countDownDate = new Date("Jul 27, 2025 20:00:00").getTime();

        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerText = days.toString().padStart(2, '0');
            document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
            document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = `<p class="font-teko text-4xl" style="color: var(--main-green); text-shadow: 0 0 10px var(--main-green);">SEDANG BERLANGSUNG</p>`;
            }
        }, 1000);
    </script>
</body>
</html>