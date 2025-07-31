<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Finals Countdown (Tailwind CSS)</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #000;
            font-family: 'Russo One', sans-serif;
            --main-green: #0FF796;
        }

        .font-teko {
            font-family: 'Teko', sans-serif;
        }

        .bg-pattern::before,
        .bg-pattern::after {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            height: 15vh;
            background-color: var(--main-green);
            /* z-index: 0 memastikan bar ini ada di atas background blur */
            z-index: 0; 
        }

        .bg-pattern::before {
            top: 0;
            clip-path: polygon(0 0, 100% 0, 100% 55%, 0 100%);
        }

        .bg-pattern::after {
            bottom: 0;
            clip-path: polygon(0 45%, 100% 0, 100% 100%, 0 100%);
        }
        
        .logo-clip {
            clip-path: polygon(0 15%, 15% 15%, 15% 0, 85% 0, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0 85%);
        }

        .background-video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1; /* Pastikan video berada di belakang konten lainnya */
        }
        #bg-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: blur(5px) brightness(0.4);
            opacity: 0.6;
            transform: scale(1.02);
        }

        /* --- ✨ ANIMATION CODE START ✨ --- */
        .start-hidden {
            opacity: 0;
        }

        @keyframes slideInDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Animation classes to apply to elements */
        .animate-slide-down {
            animation: slideInDown 0.6s ease-out forwards;
        }
        .animate-slide-up {
            animation: slideInUp 0.6s ease-out forwards;
        }

        /* Staggered delay classes */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }
        /* --- ✨ ANIMATION CODE END ✨ --- */

    </style>
</head>

<body class="text-white text-center flex flex-col min-h-screen relative bg-pattern">

    <div class="background-video-container">
        <video id="bg-video" autoplay loop muted playsinline>
            <source src="/huds/delta-force-v1/assets/videos/bg.mp4" type="video/mp4">
            Browser Anda tidak mendukung tag video.
        </video>
    </div>

    <header class="flex justify-between items-center py-5 px-10 relative z-10 h-[15vh]">
        <div class="text-base uppercase mb-12 start-hidden animate-slide-down delay-100">SCRIM - GAMEDAY</div>
        <div class="text-base uppercase mb-12 start-hidden animate-slide-down delay-200">{{ $timeoutmatch->panel->title ?? '' }}</div>
    </header>           

    <main class="flex-grow flex flex-col justify-center items-center p-5 relative z-10">
        <div class="text-center start-hidden animate-slide-up delay-300">
            <img src="/huds/delta-force-v1/assets/scrim.png" alt="Agres.Id Logo" width="300">
        </div>
        <h2 class="text-2xl tracking-wider mb-2 start-hidden animate-slide-up delay-400">{{ $timeoutmatch->panel->title ?? '' }}</h2>
        <h3 class="live-in-text text-4xl mb-2 start-hidden animate-slide-up delay-500">TIMEOUT:</h3>
        <div id="countdown" class="font-teko text-8xl md:text-9xl text-[#0FF796] leading-none start-hidden animate-slide-up delay-600">
            00:00
        </div>
    </main>

    <footer class="flex justify-around items-center py-5 px-10 relative z-10 h-[15vh]">
        <div class="uppercase mt-12 start-hidden animate-slide-up delay-500">GIHUD.GG</div>
        <div class="uppercase mt-12 start-hidden animate-slide-up delay-600">GIHUD.GG</div>
        <div class="uppercase mt-12 start-hidden animate-slide-up delay-700">GIHUD.GG</div>
    </footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const countdownEl = document.getElementById('countdown');
    const statusTextEl = document.querySelector('.live-in-text');
    const roomCode = "{{ $roomCode ?? '' }}";
    const getStateUrl = "{{ route('panel.getTimerState', $roomCode) }}";

    let localState = { last_update: 0 };
    let countdownInterval;

    function formatTime(seconds) {
        if (seconds < 0) seconds = 0;
        const min = Math.floor(seconds / 60);
        const sec = seconds % 60;
        return `${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
    }

    function updateTimerUI(state) {
        clearInterval(countdownInterval);
        statusTextEl.innerHTML = "TIMEOUT:";

        if (state.status === 'running') {
            const serverStartTime = state.started_at;
            const initialDuration = state.duration;
            
            const runCountdown = () => {
                const nowInSeconds = Math.floor(Date.now() / 1000);
                const elapsed = nowInSeconds - serverStartTime;
                const remaining = initialDuration - elapsed;
                countdownEl.innerHTML = formatTime(remaining);
                if (remaining <= 0) {
                    clearInterval(countdownInterval);
                    statusTextEl.innerHTML = "WE ARE";
                    countdownEl.innerHTML = "LIVE!";
                }
            };
            runCountdown();
            countdownInterval = setInterval(runCountdown, 1000);

        } else if (state.status === 'paused') {
            countdownEl.innerHTML = formatTime(state.remaining_seconds_on_pause);
        } else { // 'stopped'
            countdownEl.innerHTML = formatTime(state.duration);
        }
    }

    const pollServer = async () => {
        try {
            const response = await fetch(getStateUrl);
            const serverState = await response.json();

            if (serverState && serverState.last_update > localState.last_update) {
                localState = serverState;
                updateTimerUI(localState);
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    };

    pollServer();
    setInterval(pollServer, 2000);
});
</script>

</body>
</html>