<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transition - Vertical Slider (Single Play)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-green: #0FF796;
        }
        body {
            background-color: #000;
            height: 100vh;
            overflow: hidden;
        }
        
        .slider-container {
            position: fixed;
            inset: 0;
            z-index: 10;
            overflow: hidden;
        }

        .slider-panel {
            position: absolute;
            width: 100%;
            height: 50%;
            left: 0;
            background: linear-gradient(180deg, #1a1a1a, #2a2a2a, #1a1a1a);
            transition: transform 1s cubic-bezier(0.7, 0, 0.2, 1);
        }
        .slider-panel.top {
            top: 0;
            transform: translateY(-100%); /* Posisi awal di luar layar */
            border-bottom: 2px solid var(--main-green);
            box-shadow: 0 5px 25px var(--main-green);
        }
        .slider-panel.bottom {
            bottom: 0;
            transform: translateY(100%); /* Posisi awal di luar layar */
            border-top: 2px solid var(--main-green);
            box-shadow: 0 -5px 25px var(--main-green);
        }

        /* State Tertutup */
        .slider-container.is-closed .slider-panel {
            transform: translateY(0);
        }
        
        /* Logo & Teks di Tengah */
        .central-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 20;
            text-align: center;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .slider-container.is-closed .central-content {
            opacity: 1;
            transition-delay: 1s; /* Muncul setelah panel menutup */
        }
        .central-logo {
            width: 350px;
        }
        .central-title {
            font-family: 'Teko', sans-serif;
            font-size: 4rem;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: #fff;
            text-shadow: 0 0 15px var(--main-green);
        }

        /* Scanline Effect */
        .scanline {
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 4px;
            background: #fff;
            box-shadow: 0 0 20px #fff, 0 0 30px var(--main-green);
            transform: translateY(-50%) scaleX(0);
            opacity: 0;
        }
        .slider-container.is-closing .scanline,
        .slider-container.is-opening .scanline {
            opacity: 1;
            animation: scan-effect 0.4s cubic-bezier(0.7, 0, 0.2, 1);
        }

        @keyframes scan-effect {
            0% { transform: translateY(-50%) scaleX(0); }
            50% { transform: translateY(-50%) scaleX(1); }
            100% { transform: translateY(-50%) scaleX(0); transform-origin: right; }
        }
    </style>
</head>
<body>

    <div id="slider" class="slider-container">
        <div class="slider-panel top"></div>
        <div class="slider-panel bottom"></div>

        <div class="central-content">
            <img src="/huds/delta-force-v1/assets/scrim.png" alt="Event Logo" class="central-logo">
            {{-- <h1 class="central-title">GRAND FINALS</h1> --}}
        </div>

        <div class="scanline"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('slider');

            async function runTransition() {
                // TAHAP 1: MENUTUP
                slider.classList.add('is-closing');
                slider.classList.add('is-closed');
                
                await new Promise(r => setTimeout(r, 400));
                slider.classList.remove('is-closing');

                // TAHAP 2: JEDA
                await new Promise(r => setTimeout(r, 2000));

                // TAHAP 3: MEMBUKA
                slider.classList.add('is-opening');
                slider.classList.remove('is-closed');

                await new Promise(r => setTimeout(r, 400));
                slider.classList.remove('is-opening');
                
                // TAHAP 4 (ULANGI) DIHAPUS
            }

            // Mulai animasi satu kali
            runTransition();
        });
    </script>
</body>
</html>