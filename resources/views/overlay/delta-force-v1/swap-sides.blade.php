<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Swap - Full Sequence</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --team-1-color: #FD023B; /* Red */
            --team-2-color: #00B2FF; /* Blue */
            --gap-width: 8rem;
            --main-green: #0FF796;
        }
        body {
            color: #fff;
            font-family: 'Russo One', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background-color: #1a1a1a;
        }
        .font-teko { font-family: 'Teko', sans-serif; }

        .swap-container {
            position: relative;
            width: calc(480px * 2 + var(--gap-width));
            height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--gap-width);
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .swap-container.visible {
            opacity: 1;
        }

        .team-card {
            width: 480px;
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            padding: 2rem;
            padding-top: 5rem;
            position: absolute;
            transition: transform 1.2s cubic-bezier(0.7, 0, 0.3, 1), border-color 1.2s ease, filter 0.5s ease;
        }
        .team-card.left {
            left: 0;
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);
            border-left: 4px solid var(--team-1-color);
        }
        .team-card.right {
            right: 0;
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0% 100%);
            border-right: 4px solid var(--team-2-color);
        }

        .swap-container.alert-visible .team-card {
            filter: blur(4px) brightness(0.6);
        }

        .team-logo { height: 200px; margin-bottom: 1rem; }
        .scrim-logo-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .team-name { font-family: 'Teko', sans-serif; font-size: 4.5rem; text-transform: uppercase; line-height: 1; transition: color 1.2s ease; }
        .team-card.left .team-name { color: var(--team-1-color); }
        .team-card.right .team-name { color: var(--team-2-color); }
        
        .role-labels { position: absolute; top: 1.5rem; left: 50%; transform: translateX(-50%); font-family: 'Teko', sans-serif; font-size: 2.2rem; font-weight: 700; text-transform: uppercase; width: 220px; height: 40px; }
        .role-labels .role { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: opacity 0.5s ease-in-out .8s; }
        .role.attacker { color: var(--team-1-color); }
        .role.defender { color: var(--team-2-color); }
        .team-card.left .role.defender, .team-card.right .role.attacker { opacity: 0; }
        .team-card.left .role.attacker, .team-card.right .role.defender { opacity: 1; }

        .swap-container.teams-swapped .team-card.left { transform: translateX(calc(100% + var(--gap-width))); border-left-color: var(--team-2-color); }
        .swap-container.teams-swapped .team-card.left .team-name { color: var(--team-2-color); }
        .swap-container.teams-swapped .team-card.left .role.defender, .swap-container.teams-swapped .team-card.right .role.attacker { opacity: 1; }
        .swap-container.teams-swapped .team-card.left .role.attacker, .swap-container.teams-swapped .team-card.right .role.defender { opacity: 0; }

        .swap-container.teams-swapped .team-card.right { transform: translateX(calc(-100% - var(--gap-width))); border-right-color: var(--team-1-color); }
        .swap-container.teams-swapped .team-card.right .team-name { color: var(--team-1-color); }


        .swap-alert {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: rgba(15, 15, 15, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem 3rem;
            z-index: 10;
            opacity: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            pointer-events: none;
            transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .swap-alert-icon { width: 50px; height: 50px; color: var(--main-green); }
        .swap-alert-text {
            font-family: 'Teko', sans-serif; font-size: 3rem; letter-spacing: 4px;
            text-transform: uppercase; color: var(--main-green); text-shadow: 0 0 10px var(--main-green);
        }
        .swap-container.alert-visible .swap-alert {
            opacity: 1; transform: translate(-50%, -50%) scale(1);
        }
    </style>
</head>
<body data-check-url="{{ route('overlay.swap.data', $swapsidesmatch->room_code) }}">
    <div id="swap-container" class="swap-container">
        <div class="team-card left">
            <div class="role-labels"><span class="role attacker">ATTACKER</span><span class="role defender">DEFENDER</span></div>
            <div class="scrim-logo-container">
                <img id="logo-team-a" src="{{ asset('storage/' . $swapsidesmatch->teamA->logo) }}" alt="Logo" class="team-logo">
            </div>
            <h2 id="name-team-a" class="team-name">{{ $swapsidesmatch->teamA->name }}</h2>
        </div>
        <div class="team-card right">
            <div class="role-labels"><span class="role attacker">ATTACKER</span><span class="role defender">DEFENDER</span></div>
            <div class="scrim-logo-container">
                <img id="logo-team-b" src="{{ asset('storage/' . $swapsidesmatch->teamB->logo) }}" alt="Logo" class="team-logo">
            </div>
            <h2 id="name-team-b" class="team-name">{{ $swapsidesmatch->teamB->name }}</h2>
        </div>
        <div class="swap-alert">
            <svg class="swap-alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            <div class="swap-alert-text">Side Switch</div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swapContainer = document.getElementById('swap-container');
        const checkUrl = document.body.dataset.checkUrl;
        
        let lastStateHash = null;
        let isSwapped = false; 

        async function runFullSequence() {
            swapContainer.classList.add('visible');
            await new Promise(resolve => setTimeout(resolve, 2500));
            swapContainer.classList.add('alert-visible');
            await new Promise(resolve => setTimeout(resolve, 1800));
            swapContainer.classList.remove('alert-visible');

            if (isSwapped) {
                swapContainer.classList.remove('teams-swapped');
            } else {
                swapContainer.classList.add('teams-swapped');
            }
            
            isSwapped = !isSwapped;

            await new Promise(resolve => setTimeout(resolve, 3000));
            swapContainer.classList.remove('visible');
        }
        
        async function pollForChanges() {
            try {
                const response = await fetch(checkUrl);
                const data = await response.json();
                const newStateHash = data.stateHash;

                if (lastStateHash === null) {
                    lastStateHash = newStateHash;
                    return;
                }

                if (newStateHash !== lastStateHash) {
                    console.log('Perubahan data terdeteksi! Menjalankan animasi...');
                    runFullSequence();
                    lastStateHash = newStateHash;
                }

            } catch (error) {
                console.error("Polling error:", error);
            }
        }
        
        setInterval(pollForChanges, 3000);
    });
</script>
</body>
</html>