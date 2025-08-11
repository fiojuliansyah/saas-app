<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard - Desain Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: transparent;
            /* Ubah ke transparan untuk overlay */
            overflow: hidden;
            position: relative;
            font-family: 'Russo One', sans-serif;
            --main-green: #0FF796;
            --team-1-color: #FD023B;
            --team-2-color: #00B2FF;
        }

        .font-teko {
            font-family: 'Teko', sans-serif;
        }

        .text-glow {
            text-shadow: 0 0 8px var(--main-green), 0 0 15px var(--main-green);
        }

        .top-hud-container {
            position: fixed;
            top: 1rem;
            left: 0;
            right: 0;
            z-index: 30;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 10rem;
        }

        .team-display-column {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            animation: slide-down 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
        }

        .team-panel {
            display: flex;
            align-items: center;
            background-color: rgba(10, 10, 10, 0.7);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.2rem;
        }

        .team-panel.left {
            padding-left: 0.75rem;
            padding-right: 1.5rem;
            border-left: 3px solid var(--team-1-color);
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
        }

        .team-panel.right {
            padding-right: 0.75rem;
            padding-left: 1.5rem;
            border-right: 3px solid var(--team-2-color);
            clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%);
        }

        .team-flag {
            height: 14px;
            width: auto;
            margin-right: 0.75rem;
            object-fit: contain;
        }

        .team-panel.right .team-flag {
            order: 3;
            margin-right: 0;
            margin-left: 0.75rem;
        }

        .team-logo-sm {
            height: 50px;
            width: 50px;
            object-fit: contain;
            margin-right: 1rem;
        }

        .team-panel.right .team-logo-sm {
            margin-right: 0;
            margin-left: 1rem;
        }

        .team-name {
            font-size: 1rem;
            text-transform: uppercase;
            line-height: 1;
        }

        .team-score {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            line-height: 1;
            font-weight: 500;
            margin: 0 1.5rem;
            transition: transform 0.2s ease-out;
            /* Transisi untuk animasi */
        }

        .team-panel.left .team-score {
            color: var(--team-1-color);
        }

        .team-panel.right .team-score {
            color: var(--team-2-color);
        }

        /* ✨ Animasi saat skor berubah ✨ */
        .score-updated {
            transform: scale(1.3);
        }

        .role-label-bar {
            font-family: 'Teko', sans-serif;
            font-size: 1.5rem;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            line-height: 1;
            text-transform: uppercase;
            color: #000;
            opacity: 0;
            animation: slide-down 0.8s cubic-bezier(0.2, 1, 0.3, 1) 0.2s forwards;
        }

        .role-label-bar.defender {
            background-color: var(--team-2-color);
        }

        .role-label-bar.attacker {
            background-color: var(--team-1-color);
        }

        .match-info-center {
            text-align: center;
            opacity: 0;
            padding-top: 5rem;
            animation: slide-down 0.8s cubic-bezier(0.2, 1, 0.3, 1) 0.2s forwards;
        }

        .match-info-center .title {
            font-size: 1rem;
            color: #aaa;
            letter-spacing: 2px;
        }

        .match-info-center .detail {
            font-family: 'Teko', sans-serif;
            font-size: 2.5rem;
            line-height: 1;
        }

        #sponsor-slideshow-container {
            position: fixed;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            z-index: 20;
            background-color: rgba(10, 10, 10, 0.5);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
            padding: 1rem;
            width: 12rem;
        }

        #sponsor-slideshow {
            position: relative;
            width: 100%;
            height: 8rem;
        }

        .fade-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .fade-slide.active {
            opacity: 1;
        }

        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="text-white antialiased" data-url="{{ route('overlay.data', $topbarmatch->room_code) }}">

    <div class="top-hud-container">
        <div class="team-display-column left" style="animation-delay: 0.4s;">
            <div class="role-label-bar attacker">Attacker</div>
            <div class="team-panel left">
                <img id="flag-team-a"
                    src="{{ asset('assets/images/flags/' . ($topbarmatch->teamA->country ?? 'default') . '.png') }}"
                    alt="Country Flag" class="team-flag update-anim">
                <img id="logo-team-a" src="{{ asset('storage/' . $topbarmatch->teamA->logo) }}" alt="Logo"
                    class="team-logo-sm update-anim">
                <div id="name-team-a" class="team-name update-anim" style="color: var(--team-1-color);">
                    {{ $topbarmatch->teamA->short_name }}</div>
                <div id="score-team-a" class="team-score update-anim">{{ $topbarmatch->score_team_a ?? 0 }}</div>
            </div>
        </div>

        <div class="match-info-center">
            <div id="match_title" class="title">{{ $topbarmatch->panel->name ?? '' }}</div>
        </div>

        <div class="team-display-column right" style="animation-delay: 0.4s;">
            <div class="team-panel right">
                <div id="score-team-b" class="team-score update-anim">{{ $topbarmatch->score_team_b ?? 0 }}</div>
                <div id="name-team-b" class="team-name update-anim" style="color: var(--team-2-color);">
                    {{ $topbarmatch->teamB->short_name }}</div>
                <img id="logo-team-b" src="{{ asset('storage/' . $topbarmatch->teamB->logo) }}" alt="Logo"
                    class="team-logo-sm update-anim">
                <img id="flag-team-b"
                    src="{{ asset('assets/images/flags/' . ($topbarmatch->teamB->country ?? 'default') . '.png') }}"
                    alt="Country Flag" class="team-flag update-anim">
            </div>
            <div class="role-label-bar defender">Defender</div>
        </div>
    </div>

    <div id="sponsor-slideshow-container">
        <div id="sponsor-slideshow">
            <img src="/huds/delta-force-v1/assets/gihud.png" class="fade-slide active">
            <img src="/huds/delta-force-v1/assets/delta-force.png" class="fade-slide">
            <img src="/huds/delta-force-v1/assets/sponsor.png" class="fade-slide">
            <img src="/huds/delta-force-v1/assets/garena.png" class="fade-slide">
            <img src="/huds/delta-force-v1/assets/dfcid.png" class="fade-slide">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataUrl = document.body.dataset.url;

            const updateElement = (elementId, newValue, isSrc = false) => {
                const element = document.getElementById(elementId);
                if (!element) return;

                const valueToCompare = isSrc ? newValue : newValue.toString();
                // -----------------------------

                const currentValue = isSrc ? element.src : element.textContent.trim();

                if (currentValue !== valueToCompare) {
                    if (isSrc) {
                        element.src = newValue;
                    } else {
                        element.textContent = newValue;
                    }

                    // ✨ Animasi skor
                    if (elementId.startsWith('score-')) {
                        element.classList.add('score-updated');
                        setTimeout(() => {
                            element.classList.remove('score-updated');
                        }, 300);
                    }
                }
            };

            const fetchData = async () => {
                try {
                    const response = await fetch(dataUrl);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    updateElement('match_title', data.match_title);
                    updateElement('score-team-a', data.score_team_a);
                    updateElement('score-team-b', data.score_team_b);

                    updateElement('name-team-a', data.name_team_a);
                    updateElement('name-team-b', data.name_team_b);

                    updateElement('logo-team-a', data.logo_team_a, true);
                    updateElement('logo-team-b', data.logo_team_b, true);

                    updateElement('flag-team-a', data.flag_team_a, true);
                    updateElement('flag-team-b', data.flag_team_b, true);


                } catch (error) {
                    console.error("Gagal mengambil data overlay:", error);
                }
            };

            setInterval(fetchData, 2000);

            fetchData();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('#sponsor-slideshow .fade-slide');
            let i = 0;

            function next() {
                slides[i].classList.remove('active');
                i = (i + 1) % slides.length;
                slides[i].classList.add('active');
            }

            // Mulai rotasi tiap 3 detik
            setInterval(next, 3000);
        });
    </script>

</body>

</html>
