<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Format - Grand Finals</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-red: #FD023B;
            --main-green: #0FF796;
            --team-1-color: #0FF796;
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* --- ✨ FORMAT PAGE STYLING ✨ --- */
        .page-title {
            font-family: 'Teko', sans-serif;
            font-size: 3.5rem;
            letter-spacing: 5px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fade-in-down 0.8s ease forwards;
        }
        .format-container {
            display: flex;
            gap: 1.5rem;
            width: 100%;
            max-width: 1400px;
            align-items: stretch; /* Membuat semua kolom sama tinggi */
        }
        .format-col {
            flex: 1;
            opacity: 0;
            transform: translateY(30px);
            animation: fade-in-up 0.8s ease forwards;
            display: flex;
            flex-direction: column;
        }
        .col-title {
            font-family: 'Teko', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 2px;
            color: #aaa;
            margin-bottom: 1rem;
            border-left: 3px solid var(--main-green);
            padding-left: 0.75rem;
            flex-shrink: 0;
        }

        /* Kolom 1: Team Format (Diperbarui) */
        .team-format-card {
            background: rgba(255, 255, 255, 0.05);
            flex-grow: 1; /* Membuat kartu mengisi ruang vertikal */
        }
        .team-format-card img {
            width: 100%;
            height: 400px; /* Tinggi gambar ditentukan */
            object-fit: cover;
        }
        .versus-block {
            padding: 1rem;
            text-align: center;
            background: rgba(0,0,0,0.3);
        }
        .versus-block .team-name {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            line-height: 1;
            text-transform: uppercase;
            padding: 0.5rem;
            background: rgba(0,0,0,0.4);
        }
        .versus-block .vs-text {
            font-size: 2rem;
            margin: 1rem 0;
        }
        
        /* Kolom 2: Rules */
        .rules-col {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .info-panel {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.25rem;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 95% 100%, 0 100%);
            flex-grow: 1;
        }
        .info-panel .title {
            color: #aaa;
            font-size: 1rem;
            text-transform: uppercase;
        }
        .info-panel .detail {
            font-family: 'Teko', sans-serif;
            font-size: 2rem;
            line-height: 1;
            color: var(--main-green);
            font-weight: 700;
        }
        .info-panel .description {
            color: #ccc;
        }

        /* Kolom 3: Map Pool */
        .map-pool-col {
            display: flex;
            flex-direction: column;
            gap: 0.75rem; /* Jarak antar peta diperkecil */
        }
        .map-card {
            height: 70px; /* Tinggi kartu peta diperkecil */
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: 0.5rem 1rem;
            flex-grow: 1;
        }
        .map-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0,0,0,0.8), transparent 70%);
        }
        .map-name {
            position: relative;
            z-index: 1;
            font-family: 'Teko', sans-serif;
            font-size: 1.8rem; /* Ukuran font disesuaikan */
            text-transform: uppercase;
            line-height: 1;
        }

        /* Animations */
        @keyframes fade-in-down { to { opacity: 1; } }
        @keyframes fade-in-up { to { opacity: 1; transform: translateY(0); } }

    </style>
</head>
<body>
    <div class="background-video-container">
        <video id="bg-video" autoplay loop muted playsinline>
            <source src="/huds/delta-force-v1/assets/videos/bg.mp4" type="video/mp4">
        </video>
    </div>

    <main>
        <h1 class="page-title">FORMAT</h1>
        <div class="format-container">
            <div class="format-col" style="animation-delay: 0.2s;">
                <h3 class="col-title">TEAM FORMAT</h3>
                <div class="team-format-card">
                    <img src="/huds/delta-force-v1/assets/format.jpg" alt="Match Cover">
                    <div class="versus-block">
                        <div class="team-name" style="color: var(--main-red);">ATTACKER</div>
                        <div class="vs-text">VERSUS</div>
                        <div class="team-name" style="color: var(--team-1-color, #0FF796);">DEFENDER</div>
                    </div>
                </div>
            </div>

            <div class="format-col rules-col" style="animation-delay: 0.4s;">
                <h3 class="col-title">RULES</h3>
                <div class="info-panel">
                    <div class="title">Win Condition</div>
                    <div class="detail">First to win 2 Maps</div>
                </div>
                <div class="info-panel">
                    <div class="title">Game Mode</div>
                    <div class="detail">Victory Unite</div>
                </div>
                <div class="info-panel">
                    <div class="title">Game 1</div>
                    <div class="description">Map and side are randomly selected</div>
                </div>
                 <div class="info-panel">
                    <div class="title">Game 2</div>
                    <div class="description">Sides Swap</div>
                </div>
            </div>

            <div class="format-col map-pool-col" style="animation-delay: 0.6s;">
                <h3 class="col-title">MAP POOL</h3>
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/ascension.png');">
                    <div class="map-name">Ascension</div>
                </div>
                {{-- <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/shafted.jpg');">
                    <div class="map-name">Shafted</div>
                </div> --}}
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/cracked.png');">
                    <div class="map-name">Cracked</div>
                </div>
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/threshold.png');">
                    <div class="map-name">Threshold</div>
                </div>
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/train.png');">
                    <div class="map-name">Train Wreck</div>
                </div>
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/trench-lines.png');">
                    <div class="map-name">Trench Lines</div>
                </div>
                {{-- <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/knife.png');">
                    <div class="map-name">Knife Edge</div>
                </div> --}}
                <div class="map-card" style="background-image: url('/huds/delta-force-v1/assets/maps/cyclone.png');">
                    <div class="map-name">CyClone</div>
                </div>
            </div>
        </div>
    </main>
    
    <script></script>
</body>
</html>