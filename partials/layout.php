<?php
/** Kerangka tampilan (View) PWA mahasiswa */
function pwa_open(string $title): void { ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= h($title) ?> - Meta Sarpras</title>
  <meta name="theme-color" content="#14284b">
  <link rel="icon" href="logo.png">
  <link rel="apple-touch-icon" href="logo.png">
  <link rel="manifest" href="manifest.json">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="auth.js" defer></script>
</head>
<body>
<?php include __DIR__ . '/sprite.php';
}

/** $mode: 'dark' (judul + subjudul) atau 'back' (tombol kembali) */
function pwa_header(array $u, string $judul, string $mode = 'back', string $kecil = '', string $kembali = 'beranda.php'): void { ?>
  <div class="wrap">
    <header<?= $mode === 'dark' ? ' class="dark"' : '' ?>>
      <div class="in"><a class="logo" href="beranda.php"><img src="logo.png" alt="Politeknik Meta Industri Cikarang"></a>
        <?php if ($mode === 'back'): ?><a class="back" href="<?= h($kembali) ?>" aria-label="Kembali"><svg><use href="#back" /></svg></a><?php endif; ?>
        <h1><?= h($judul) ?><?= $kecil ? '<small>' . h($kecil) . '</small>' : '' ?></h1><a class="avatar" href="profil.php" title="Profil Mahasiswa"><?= h(inisial($u['nama_mahasiswa'])) ?></a>
      </div>
    </header>
<?php }

function pwa_nav(string $aktif): void { ?>
  </div>
  <nav>
    <div class="in">
      <a href="beranda.php" class="<?= $aktif === 'beranda' ? 'on' : '' ?>"><svg><use href="#home" /></svg>Beranda</a><a href="pengaduan.php" class="<?= $aktif === 'pengaduan' ? 'on' : '' ?>"><svg><use href="#flag" /></svg>Pengaduan</a><a href="peminjaman.php" class="<?= $aktif === 'peminjaman' ? 'on' : '' ?>"><svg><use href="#loan" /></svg>Peminjaman</a><a href="profil.php" class="<?= $aktif === 'profil' ? 'on' : '' ?>"><svg><use href="#user" /></svg>Profil</a>
    </div>
  </nav>
</body>
</html>
<?php }

function pwa_ikon(string $nama): string {
    if (preg_match('/sound|speaker|audio|mic/i', $nama)) return 'spk';
    if (preg_match('/tang|obeng|kunci|toolset|palu/i', $nama)) return 'wr';
    if (preg_match('/meter|ukur|osiloskop/i', $nama)) return 'gauge';
    return 'loan';
}
function pwa_badge_pengaduan(string $s, bool $flush = false): string {
    $st = $flush ? ' style="margin:0"' : '';
    return match ($s) {
        'Selesai' => "<span class=\"badge\"$st>Selesai</span>",
        'Sedang diproses' => "<span class=\"badge act\"$st>Proses</span>",
        default => "<span class=\"badge act\"$st>Menunggu</span>",
    };
}
function pwa_badge_pinjam(string $s): string {
    return match ($s) {
        'Telah Dikembalikan' => '<span class="badge">Telah dikembalikan</span>',
        'Disetujui' => '<span class="badge">Disetujui</span>',
        'Dipinjam' => '<span class="badge act">Sedang dipinjam</span>',
        'Ditolak' => '<span class="badge bad">Ditolak</span>',
        default => '<span class="badge act">Menunggu konfirmasi</span>',
    };
}
function pwa_judul(string $desk, int $n = 48): string {
    $t = mb_strimwidth(preg_replace('/\s+/', ' ', trim($desk)), 0, $n, '…', 'UTF-8');
    return $t;
}
