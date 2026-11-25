<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#0b1f3a">
    <title>Meta Sarpras</title>
    <link rel="icon" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0
        }

        html,
        body {
            height: 100%
        }

        body {
            font-family: Inter, system-ui, sans-serif;
            background: #0b1f3a;
            color: #fff;
            overflow: hidden;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent
        }

        .s {
            position: relative;
            height: 100%;
            max-width: 480px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: env(safe-area-inset-top) 24px calc(28px + env(safe-area-inset-bottom));
            overflow: hidden
        }

        .ring {
            position: absolute;
            border: 1px solid #ffffff12;
            border-radius: 50%;
            pointer-events: none
        }

        .r1 {
            width: 300px;
            height: 300px;
            top: -130px;
            right: -90px
        }

        .r2 {
            width: 440px;
            height: 440px;
            top: -190px;
            right: -150px
        }

        .c {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-top: -40px
        }

        .tile {
            width: min(160px, 42vw);
            aspect-ratio: 1;
            border-radius: 28px;
            background: #e5e5e5;
            display: grid;
            place-items: center;
            box-shadow: 0 12px 32px #0006;
            animation: in .7s cubic-bezier(.2, .8, .2, 1) both
        }

        .tile img {
            width: 68%;
            height: 68%;
            object-fit: contain
        }

        h1 {
            font-size: clamp(34px, 9vw, 40px);
            font-weight: 300;
            letter-spacing: .01em;
            margin-top: 36px;
            animation: in .7s .15s both
        }

        .bar {
            width: 44px;
            height: 4px;
            border-radius: 2px;
            background: #ff7a00;
            margin: 16px 0 22px;
            animation: grow .6s .35s both
        }

        p.t {
            font-size: 17px;
            line-height: 1.55;
            color: #b9c6da;
            font-weight: 300;
            max-width: 240px;
            animation: in .7s .3s both
        }

        .dots {
            display: flex;
            gap: 7px;
            align-items: center;
            margin-bottom: 28px
        }

        .dots i {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #3a4b66;
            transition: .3s
        }

        .dots i.on {
            width: 26px;
            border-radius: 4px;
            background: #ff7a00
        }

        .f {
            text-align: center
        }

        .f b {
            display: block;
            font-size: 13px;
            font-weight: 600
        }

        .f span {
            font-size: 11.5px;
            color: #9fb0c8;
            display: block;
            margin-top: 4px
        }

        .skip {
            position: absolute;
            top: calc(16px + env(safe-area-inset-top));
            left: 20px;
            background: none;
            border: 0;
            color: #9fb0c8;
            font: 500 12px Inter, sans-serif;
            padding: 8px
        }

        @keyframes in {
            from {
                opacity: 0;
                transform: translateY(14px)
            }
        }

        @keyframes grow {
            from {
                width: 0;
                opacity: 0
            }
        }

        @media(prefers-reduced-motion:reduce) {
            * {
                animation: none !important;
                transition: none !important
            }
        }
    </style>
</head>

<body>
    <main class="s" id="s" aria-label="Meta Sarpras">
        <div class="ring r1"></div>
        <div class="ring r2"></div>
        <div class="c">
            <div class="tile"><img
                    src="logo.png"
                    alt="Logo Politeknik Meta Industri Cikarang"></div>
            <h1>Meta Sarpras</h1>
            <div class="bar"></div>
            <p class="t">Satu akses untuk fasilitas kampus yang lebih baik.</p>
        </div>
        <div class="dots" aria-hidden="true"><i class="on"></i><i></i><i></i></div>
        <div class="f"><b>Politeknik Meta Industri Cikarang</b><span>Sarana &amp; prasarana • Layanan mahasiswa</span>
        </div>
    </main>
    <script>
        const d = [...document.querySelectorAll('.dots i')]; let n = 0, done = false;
        const go = () => { if (done) return; done = true; document.body.style.transition = 'opacity .25s'; document.body.style.opacity = 0; setTimeout(() => location.replace('login.php'), 230) };
        const tick = setInterval(() => { n++; if (n > 2) { clearInterval(tick); go(); return } d.forEach((x, i) => x.classList.toggle('on', i == n)) }, 900);
        document.body.addEventListener('click', go); document.addEventListener('keydown', go);
    </script>
</body>

</html>