<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require();
$p = Pengaduan::findMilik((int)($_GET['id'] ?? 0), (int)$u['id_mahasiswa']);
if (!$p) redirect('pengaduan.php');
$tg = Tanggapan::byPengaduan((int)$p['id_pengaduan']);
$foto = foto_url($p['foto_pengaduan']);
pwa_open('Detail Pengaduan'); pwa_header($u, 'Detail pengaduan', 'back', '', 'pengaduan.php'); ?>
    <main>
      <div class="head"><span>PENGADUAN #<?= (int)$p['id_pengaduan'] ?></span><?= pwa_badge_pengaduan($p['status_pengaduan']) ?></div>
      <h2 class="title"><?= h(pwa_judul($p['deskripsi_pengaduan'], 60)) ?></h2>
      <p class="sub">Lihat informasi laporan dan pantau proses penanganan fasilitas.</p>
      <div class="card">
        <h3>Informasi pengaduan</h3>
        <p class="val" style="font-weight:500;color:#6b7a93;margin:0 0 16px"><?= nl2br(h($p['deskripsi_pengaduan'])) ?></p>
        <div class="gr">
          <div><small>Tanggal pengajuan</small><b><?= h(tgl($p['tanggal'])) ?></b></div>
          <div><small>Status</small><b><?= h($p['status_pengaduan']) ?></b></div>
          <div><small>Pelapor</small><b><?= h($u['nama_mahasiswa']) ?></b></div>
          <div><small>Petugas</small><b><?= h($p['nama_admin'] ?? 'Belum ditugaskan') ?></b></div>
        </div>
      </div>
      <div class="card">
        <h3>Foto bukti pengaduan</h3>
        <div class="img one"><?php if ($foto): ?><img src="<?= h($foto) ?>" alt="Foto bukti pengaduan"><?php else: ?><svg style="width:36px;height:36px;color:#fff"><use href="#cam" /></svg><?php endif; ?></div>
      </div>
      <div class="card tl">
        <h3>Riwayat penanganan <span><?= count($tg) + 1 ?> aktivitas</span></h3>
        <?php foreach ($tg as $t): ?>
        <div class="ev"><div class="dot"></div><div><h5>Tanggapan petugas</h5><small><?= h(tgl($t['tgl'])) ?> • <?= h($t['admin']) ?></small><p><?= h($t['isi']) ?></p></div></div>
        <?php endforeach; ?>
        <div class="ev"><div class="dot"></div><div><h5>Pengaduan dibuat</h5><small><?= h(tgl($p['tanggal'])) ?> • <?= h($u['nama_mahasiswa']) ?></small><p>Laporan berhasil dicatat oleh sistem.</p></div></div>
      </div>
      <div class="card">
        <h3>Tanggapan petugas</h3>
        <?php foreach ($tg as $t): ?><div class="resp"><?= nl2br(h($t['isi'])) ?></div><?php endforeach; ?>
        <?php if (!$tg): ?><div class="resp">Belum ada tanggapan dari petugas. Laporan Anda sedang menunggu tindak lanjut.</div><?php endif; ?>
      </div>
      <a class="btn main" href="pengaduan.php">Kembali ke pengaduan saya</a>
    </main>
<?php pwa_nav('pengaduan');
