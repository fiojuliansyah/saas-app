<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinematic Replay - Grand Finals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-green: #0FF796;
            --gold-color: #FFD700;
            --main-red: #FD023B;
        }
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Russo-One', sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        .font-teko { font-family: 'Teko', sans-serif; }

        /* Wadah Utama */
        .replay-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }
        
        /* Video Replay Utama */
        #replay-video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        /* Bar Hitam (Letterbox) */
        .letterbox {
            position: absolute;
            left: 0;
            width: 100%;
            height: 15vh; /* Tinggi bar */
            background-color: #000;
            z-index: 2;
            display: flex;
            align-items: center;
            padding: 0 4rem;
        }
        .letterbox.top {
            top: 0;
            transform: translateY(-100%);
            animation: slide-in-top 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards;
        }
        .letterbox.bottom {
            bottom: 0;
            transform: translateY(100%);
            animation: slide-in-bottom 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards;
        }

        /* Konten di dalam bar */
        .bar-content {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            animation: fade-in-content 0.8s ease-out 1.2s forwards;
        }

        /* Top Bar Content */
        .replay-tag {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            letter-spacing: 3px;
            color: var(--main-red);
            font-weight: 700;
            line-height: 1;
            animation: pulse 1.5s infinite;
        }
        .match-context {
            font-size: 1.2rem;
            color: #aaa;
            letter-spacing: 1px;
        }

        /* Bottom Bar Content */
        .player-focus {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .player-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid var(--main-green);
            object-fit: cover;
        }
        .player-info .player-ign {
            font-size: 1.5rem;
            line-height: 1;
        }
        .player-info .team-name {
            font-size: 1rem;
            color: #aaa;
        }
        .situation-text {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            color: var(--gold-color);
            text-shadow: 0 0 10px var(--gold-color);
            font-weight: 700;
            line-height: 1;
        }

        .event-logo-container {
            height: 100px; /* Lebih besar dari header, jadi menonjol */
            width: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .event-logo { height: 60px; }
        
        /* Animasi */
        @keyframes pulse { 0%, 100% { opacity: 1; text-shadow: 0 0 8px var(--main-red); } 50% { opacity: 0.6; text-shadow: none; } }
        @keyframes slide-in-top {
            to { transform: translateY(0); }
        }
        @keyframes slide-in-bottom {
            to { transform: translateY(0); }
        }
        @keyframes fade-in-content {
            to { opacity: 1; }
        }

    </style>
</head>
<body>

    <div class="replay-container">

        <div class="letterbox top">
            <div class="bar-content">
                <div class="replay-tag">REPLAY</div>
                <img src="/huds/delta-force-v1/assets/scrim.png" alt="Event Logo" class="event-logo">
            </div>
        </div>

        <div class="letterbox bottom">
            <div class="bar-content">
                <div class="player-focus">
                    <!-- <img src="/hud/delta-force-v1/assets/teams/oxygen.png" alt="Player Photo" class="player-photo">
                    <div class="player-info">
                        <div class="player-ign">VORTEX</div>
                        <div class="team-name">OXIGEN</div>
                    </div> -->
                </div>
                <div class="situation-text">
                    SITUATION
                </div>
            </div>
        </div>
    </div>

</body>
</html>