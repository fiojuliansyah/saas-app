<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Takeover Alert (3D Centered)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-green: #0FF796;
            --main-red: #FD023B;
        }
        body {
            background-color: #000;
            height: 100vh;
            overflow: hidden;
            font-family: 'Russo One', sans-serif;
            perspective: 1000px;
        }
        .font-teko { font-family: 'Teko', sans-serif; }
        .background-video-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
        }
        #bg-video {
            width: 100%; height: 100%; object-fit: cover;
            filter: blur(5px) brightness(0.4);
        }

        /* Site Takeover Alert Styling */
        .site-takeover-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 900px;
            z-index: 100;
            transform-style: preserve-3d;
            opacity: 0;
            visibility: hidden;
            transform: translate(-50%, -50%);
        }
        .site-takeover-alert.show {
            visibility: visible;
            animation: fly-in-3d 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
        }
        .site-takeover-alert.hide {
            visibility: visible;
            animation: fly-out-3d 0.6s cubic-bezier(0.6, 0, 0.8, 0) forwards;
        }
        
        .alert-background {
            position: absolute;
            width: 100%;
            height: 150px;
            background: linear-gradient(90deg, rgba(10,10,10,0.8), rgba(50, 10, 10, 0.7));
            backdrop-filter: blur(5px);
            border: 2px solid rgba(253, 2, 2, 0.5);
            /* ✨ Bentuk diubah menjadi simetris ✨ */
            clip-path: polygon(10% 0, 90% 0, 100% 100%, 0 100%);
        }

        .alert-content {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            padding: 0 2rem;
            transform: translateZ(40px);
            height: 120px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .alert-icon {
            width: 60px;
            height: 60px;
            background-color: var(--main-red);
            clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            font-family: 'Teko', sans-serif;
        }

        .alert-text {
            font-family: 'Teko', sans-serif;
            font-size: 3rem;
            text-transform: uppercase;
            line-height: 1;
            /* ✨ Teks dibuat rata tengah ✨ */
            text-align: center;
        }

        .alert-text span { display: block; }

        .highlight-text {
            font-size: 4.5rem;
            font-weight: 700;
            color: var(--main-red);
            text-shadow: 0 0 15px var(--main-red);
        }

        .title-text {
            color: #fff;
        }

        /* --- Animasi 3D (Tidak Berubah) --- */
        @keyframes fly-in-3d {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) translateZ(-800px) rotateY(30deg);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) translateZ(0) rotateY(0);
            }
        }
        @keyframes fly-out-3d {
            from {
                opacity: 1;
                transform: translate(-50%, -50%) translateZ(0);
            }
            to {
                opacity: 0;
                transform: translate(-50%, -50%) translateZ(400px);
            }
        }
    </style>
</head>
<body>

    <div id="takeover-alert" class="site-takeover-alert">
        <div class="alert-background"></div>
        <div class="alert-content">
            <div class="alert-icon">A</div>
            <div class="alert-text">
                <span class="title-text">SITE TELAH DIREBUT OLEH</span>
                <span class="highlight-text">ATTACKER</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertElement = document.getElementById('takeover-alert');

            function showSiteTakeoverAlert() {
                alertElement.classList.remove('hide');
                alertElement.classList.add('show');

                setTimeout(() => {
                    alertElement.classList.remove('show');
                    alertElement.classList.add('hide');
                }, 4000);
            }

            // Tampilkan notifikasi 2 detik setelah halaman dimuat
            setTimeout(showSiteTakeoverAlert, 2000);
        });
    </script>
</body>
</html>