<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require(); $id = (int)$u['id_user'];
$st = $_GET['status'] ?? ''; if (!in_array($st, ['', 'Menunggu', 'Sedang diproses', 'Selesai'], true)) $st = '';
$q = trim($_GET['q'] ?? '');
$cnt = Pengaduan::hitung($id); $rows = Pengaduan::byMahasiswa($id, $st, $q);
$chip = fn(string $label, string $val, int $n) => '<a class="chip' . ($st === $val ? ' on' : '') . '" href="?' . h(http_build_query(array_filter(['status' => $val, 'q' => $q], fn($x) => $x !== ''))) . '">' . h($label) . ' (' . $n . ')</a>';
pwa_open('Pengaduan'); pwa_header($u, 'Pengaduan fasilitas', 'dark', 'LAYANAN PENGADUAN'); ?>
    <main>
      <h2 class="title">Pengaduan saya</h2>
      <p class="sub">Temukan kembali keluhan dan pantau tindak lanjutnya di sini.</p>
      <a class="btn main" href="pengaduan-tambah.php" style="margin:0">+ &nbsp;Buat pengaduan</a>
      <form method="get"><?php if ($st !== ''): ?><input type="hidden" name="status" value="<?= h($st) ?>"><?php endif; ?>
        <label class="search"><svg><use href="#srch" /></svg><input type="search" name="q" value="<?= h($q) ?>" placeholder="Cari isi aduan..."></label></form>
      <div class="chips"><?= $chip('Semua', '', $cnt['Semua']) . $chip('Menunggu', 'Menunggu', $cnt['Menunggu']) . $chip('Proses', 'Sedang diproses', $cnt['Sedang diproses']) . $chip('Selesai', 'Selesai', $cnt['Selesai']) ?></div>
      <div class="found"><span><?= count($rows) ?> pengaduan ditemukan</span><span>Terbaru ↓</span></div>
      <div class="list">
        <?php foreach ($rows as $r): ?>
        <article class="item">
          <div class="card-top"><small class="k">PENGADUAN #<?= (int)$r['id_pengaduan'] ?></small><?= pwa_badge_pengaduan($r['status_pengaduan'], true) ?></div>
          <h4 style="font-size:15px;margin:12px 0 0"><?= h(pwa_judul($r['deskripsi_pengaduan'])) ?></h4>
          <p class="desc"><?= h(mb_strimwidth($r['deskripsi_pengaduan'], 0, 140, '…', 'UTF-8')) ?></p>
          <div class="foot">
            <div><small style="font-size:12px;margin-bottom:6px;letter-spacing:0"><?= h(tgl($r['tanggal'])) ?></small>
              <?php if ($r['foto_pengaduan']): ?><span class="tag blue">1 lampiran</span><?php endif; ?>
              <?php if ((int)$r['jml_tanggapan'] > 0): ?><span class="tag blue"><?= (int)$r['jml_tanggapan'] ?> tanggapan</span><?php endif; ?></div>
            <a href="pengaduan-detail.php?id=<?= (int)$r['id_pengaduan'] ?>">Lihat detail →</a>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php if (!$rows): ?><div class="tip" style="margin-top:20px"><svg><use href="#info" /></svg><span>Tidak ada pengaduan yang cocok.</span></div>
      <?php else: ?><div class="tip" style="margin-top:20px"><svg><use href="#info" /></svg><span>Tanggapan pengaduan akan muncul di sini. Pantau statusnya untuk mengetahui tindak lanjut.</span></div><?php endif; ?>
    </main>
<?php pwa_nav('pengaduan');
