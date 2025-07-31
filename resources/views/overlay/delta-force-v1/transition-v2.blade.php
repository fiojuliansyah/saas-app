<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Stinger Transition</title>
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

        .stinger-container {
            position: fixed;
            inset: 0;
            z-index: 100;
            overflow: hidden;
            pointer-events: none;
            display: flex;
            justify-content: space-between;
        }

        .gate-side {
            display: flex;
            height: 100%;
        }
        
        .gate-blade {
            width: 16.7vw;
            height: 100%;
            background: linear-gradient(to right, #0FF796, #0ccf7e);
            transition: transform 1.2s cubic-bezier(0.8, 0, 0.2, 1);
        }
        .gate-side.left .gate-blade {
            transform: translateX(-100vw);
            border-right: 2px solid var(--main-green);
            box-shadow: -5px 0 15px var(--main-green);
        }
        .gate-side.right .gate-blade {
            transform: translateX(100vw);
            border-left: 2px solid var(--main-green);
            box-shadow: 5px 0 15px var(--main-green);
        }

        /* State Tertutup */
        .stinger-container.is-closed .gate-blade {
            transform: translateX(0);
        }

        /* Delay bertahap dari luar ke dalam */
        .gate-side.left .gate-blade:nth-child(1) { transition-delay: 0s; }
        .gate-side.left .gate-blade:nth-child(2) { transition-delay: 0.05s; }
        .gate-side.left .gate-blade:nth-child(3) { transition-delay: 0.1s; }
        .gate-side.right .gate-blade:nth-child(1) { transition-delay: 0.1s; }
        .gate-side.right .gate-blade:nth-child(2) { transition-delay: 0.05s; }
        .gate-side.right .gate-blade:nth-child(3) { transition-delay: 0s; }


        .stinger-flash-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 250px;
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0;
            z-index: 110;
        }
        /* Memicu animasi logo saat gerbang tertutup */
        .stinger-container.is-closed .stinger-flash-logo {
            /* Menggunakan animasi baru dengan durasi lebih panjang */
            animation: reveal-logo 2s ease-in-out forwards;
            animation-delay: 0.8s;
        }
        
        /* Animasi logo diubah dari flash menjadi reveal-hold-hide */
        @keyframes reveal-logo {
            0% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
            20% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
            80% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(0.8); opacity: 0; }
        }

    </style>
</head>
<body>
    <div id="stinger" class="stinger-container">
        <div class="gate-side left">
            <div class="gate-blade"></div>
            <div class="gate-blade"></div>
            <div class="gate-blade"></div>
        </div>
        <img src="/huds/delta-force-v1/assets/scrim.png" alt="Flash Logo" class="stinger-flash-logo">
        <div class="gate-side right">
            <div class="gate-blade"></div>
            <div class="gate-blade"></div>
            <div class="gate-blade"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stinger = document.getElementById('stinger');

            async function runSingleTransition() {
                // TAHAP 1: MENUTUP
                stinger.classList.add('is-closed');
                
                // TAHAP 2: JEDA (logo tampil lebih lama)
                await new Promise(resolve => setTimeout(resolve, 3200));

                // TAHAP 3: MEMBUKA
                stinger.classList.remove('is-closed');

                // SIKLUS ULANG DIHAPUS
            }

            // Jalankan transisi satu kali setelah halaman siap
            runSingleTransition();
        });
    </script>
</body>
</html>