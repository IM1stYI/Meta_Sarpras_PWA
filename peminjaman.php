<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require(); $id = (int)$u['id_mahasiswa'];
$f = $_GET['status'] ?? ''; if (!in_array($f, ['', 'aktif', 'selesai'], true)) $f = '';
$q = trim($_GET['q'] ?? '');
$cnt = Peminjaman::hitung($id); $rows = Peminjaman::byMahasiswa($id, $f, $q);
$chip = fn(string $label, string $val, int $n) => '<a class="chip' . ($f === $val ? ' on' : '') . '" href="?' . h(http_build_query(array_filter(['status' => $val, 'q' => $q], fn($x) => $x !== ''))) . '">' . h($label) . ' (' . $n . ')</a>';
pwa_open('Peminjaman'); pwa_header($u, 'Peminjaman alat', 'dark', 'LAYANAN MAHASISWA'); ?>
    <main>
      <h2 class="title">Peminjaman saya</h2>
      <p class="sub">Kelola peminjaman alat praktikum dan laboratorium dengan mudah.</p>
      <a class="btn main" href="peminjaman-tambah.php" style="margin:0">+ &nbsp;Tambah peminjaman</a>
      <form method="get"><?php if ($f !== ''): ?><input type="hidden" name="status" value="<?= h($f) ?>"><?php endif; ?>
        <label class="search"><svg><use href="#srch" /></svg><input type="search" name="q" value="<?= h($q) ?>" placeholder="Cari nama alat praktikum..."></label></form>
      <div class="chips"><?= $chip('Semua', '', $cnt['semua']) . $chip('Aktif', 'aktif', $cnt['aktif']) . $chip('Dikembalikan', 'selesai', $cnt['selesai']) ?></div>
      <div class="found"><span><?= count($rows) ?> peminjaman ditemukan</span><span>Terbaru ↓</span></div>
      <div class="list">
        <?php foreach ($rows as $r): ?>
        <article class="item">
          <div class="row">
            <div class="ico"><svg><use href="#<?= pwa_ikon($r['nama_peminjaman']) ?>" /></svg></div>
            <div><small class="k">PEMINJAMAN #<?= (int)$r['id_peminjaman'] ?></small><h4><?= h($r['nama_peminjaman']) ?></h4><span class="j">Jumlah: <?= (int)$r['jumlah'] ?> unit</span></div>
          </div>
          <?= pwa_badge_pinjam($r['status_peminjaman']) ?>
          <div class="cols"><div>Tanggal pinjam<b><?= h(tgl($r['tgl_peminjaman'], true)) ?></b></div><div>Batas kembali<b><?= h(tgl($r['batas_kembali'], true)) ?></b></div></div>
          <div class="foot">
            <div><small>DENDA KETERLAMBATAN</small><strong class="<?= (int)$r['denda'] > 0 ? '' : 'ok' ?>"><?= h(rupiah($r['denda'])) ?></strong></div>
            <a href="peminjaman-detail.php?id=<?= (int)$r['id_peminjaman'] ?>">Lihat detail →</a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php if (!$rows): ?><div class="tip" style="margin-top:20px"><svg><use href="#info" /></svg><span>Tidak ada peminjaman yang cocok.</span></div><?php endif; ?>
      <div class="tip" style="margin-top:20px"><svg><use href="#clk" /></svg><span>Kembalikan alat sesuai batas waktu dan dalam kondisi baik untuk menghindari denda.</span></div>
    </main>
<?php pwa_nav('peminjaman');
