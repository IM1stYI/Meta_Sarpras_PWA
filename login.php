<?php
require __DIR__ . '/../app/bootstrap.php';
if (mhs_user()) redirect('beranda.php');
$err = ''; $nimLama = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nimLama = trim($_POST['nim'] ?? ''); $pw = $_POST['password'] ?? '';
    if (!csrf_ok($_POST['csrf'] ?? null)) { $err = 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.'; }
    elseif ($nimLama === '' || $pw === '') { $err = 'NIM dan password wajib diisi.'; }
    elseif ($u = User::loginMahasiswa($nimLama, $pw)) {
        session_regenerate_id(true);
        $_SESSION['mhs_id'] = (int)$u['id_user'];
        redirect('beranda.php');
    } else { $err = 'NIM atau password salah. Silakan coba lagi.'; }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <meta name="theme-color" content="#0b1f3a">
    <title>Masuk - Meta Sarpras</title>
    <link rel="icon" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0
        }

        body {
            font-family: Inter, system-ui, sans-serif;
            background: #0b1f3a;
            color: #12233f;
            min-height: 100vh
        }

        .s {
            position: relative;
            min-height: 100vh;
            max-width: 480px;
            margin: 0 auto;
            padding: calc(48px + env(safe-area-inset-top)) 24px calc(28px + env(safe-area-inset-bottom));
            display: flex;
            flex-direction: column;
            align-items: center;
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

        .tile {
            width: 84px;
            height: 84px;
            border-radius: 20px;
            background: #e5e5e5;
            display: grid;
            place-items: center;
            box-shadow: 0 10px 28px #0006
        }

        .tile img {
            width: 68%;
            height: 68%;
            object-fit: contain
        }

        h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 300;
            margin-top: 22px;
            text-align: center
        }

        .bar {
            width: 40px;
            height: 4px;
            border-radius: 2px;
            background: #ff7a00;
            margin: 12px 0
        }

        .sub {
            color: #b9c6da;
            font-size: 14px;
            font-weight: 300;
            text-align: center;
            margin-bottom: 28px
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            width: 100%;
            box-shadow: 0 16px 40px #0005;
            animation: in .5s both
        }

        @keyframes in {
            from {
                opacity: 0;
                transform: translateY(14px)
            }
        }

        .card h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px
        }

        .card>p {
            font-size: 13px;
            color: #7b8798;
            margin-bottom: 6px
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin: 16px 0 8px
        }

        label i {
            color: #ef4444;
            font-style: normal
        }

        .in {
            width: 100%;
            padding: 14px;
            border: 1px solid #e3e8ef;
            border-radius: 12px;
            font: inherit;
            font-size: 14px;
            outline: 0;
            background: #fff
        }

        .in:focus {
            border-color: #ff7a00;
            box-shadow: 0 0 0 3px #ff7a0022
        }

        .in.err {
            border-color: #d93a3a
        }

        .pw {
            position: relative
        }

        .pw button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: 0;
            font-size: 16px;
            padding: 8px;
            cursor: pointer
        }

        .em {
            color: #d93a3a;
            font-size: 12px;
            margin-top: 12px;
            display: none
        }

        .em.on {
            display: block
        }

        .rem {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #566274;
            margin: 14px 0 0
        }

        .rem input {
            width: 16px;
            height: 16px;
            accent-color: #ff7a00
        }

        .btn {
            width: 100%;
            margin-top: 22px;
            padding: 15px;
            border: 0;
            border-radius: 12px;
            background: #ff7a00;
            color: #fff;
            font: 700 15px Inter, sans-serif;
            cursor: pointer;
            transition: .15s
        }

        .btn:hover {
            background: #ec6e00
        }

        .btn:active {
            transform: scale(.98)
        }

        .hint {
            text-align: center;
            font-size: 12px;
            color: #7b8798;
            margin-top: 16px
        }

        .foot {
            margin-top: auto;
            padding-top: 28px;
            text-align: center;
            color: #fff
        }

        .foot b {
            display: block;
            font-size: 13px;
            font-weight: 600
        }

        .foot span {
            font-size: 11.5px;
            color: #9fb0c8
        }

        :focus-visible {
            outline: 2px solid #ff7a00;
            outline-offset: 2px
        }

        @media(prefers-reduced-motion:reduce) {
            .card {
                animation: none
            }
        }
    </style>
</head>

<body>
    <main class="s">
        <div class="ring r1"></div>
        <div class="ring r2"></div>
        <div class="tile"><img
                src="logo.png"
                alt="Logo Politeknik Meta"></div>
        <h1>Meta Sarpras</h1>
        <div class="bar"></div>
        <p class="sub">Satu akses untuk fasilitas kampus yang lebih baik.</p>
        <form class="card" id="login" method="post" action="login.php" novalidate>
            <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
            <h2>Masuk Mahasiswa</h2>
            <p>Gunakan NIM dan password akun Anda.</p>
            <label for="nim">NIM <i>*</i></label><input class="in" id="nim" name="nim" inputmode="numeric" autocomplete="username" placeholder="Contoh: 2425010" value="<?= h($nimLama) ?>" required autofocus>
            <label for="pw">Password <i>*</i></label>
            <div class="pw"><input class="in" id="pw" name="password" type="password" autocomplete="current-password" placeholder="Masukkan password" required><button type="button" id="eye"
                    aria-label="Tampilkan password">👁️</button></div>
            <p class="em<?= $err ? ' on' : '' ?>" id="err" role="alert"><?= h($err) ?></p><button class="btn" type="submit">Masuk</button>
            <p class="hint">Akun demo: NIM <b>2425010</b> / password <b>mahasiswa</b></p>
        </form>
        <div class="foot"><b>Politeknik Meta Industri Cikarang</b><span>Sarana &amp; prasarana • Layanan
                mahasiswa</span></div>
    </main>
    <script src="auth.js"></script>
    <script>
        var pw = document.getElementById('pw'), eye = document.getElementById('eye');
        eye.addEventListener('click', function () { var h = pw.type === 'password'; pw.type = h ? 'text' : 'password'; eye.setAttribute('aria-label', h ? 'Sembunyikan password' : 'Tampilkan password'); });
    </script>
</body>

</html>