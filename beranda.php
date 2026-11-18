<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require(); $id = (int)$u['id_mahasiswa'];
$cP = Pengaduan::hitung($id); $cL = Peminjaman::hitung($id);
$pengaduan = Pengaduan::byMahasiswa($id)[0] ?? null;
$pinjam = Peminjaman::byMahasiswa($id)[0] ?? null;
$nmDepan = explode(' ', trim($u['nama_mahasiswa']))[0];
pwa_open('Beranda'); pwa_header($u, 'Meta Sarpras', 'dark', 'LAYANAN MAHASISWA'); ?>
    <main>
      <section class="welcome">
        <div class="eb">PORTAL MAHASISWA</div>
        <h2>Selamat datang, <?= h($nmDepan) ?>!</h2>
        <p>NIM <?= h($u['nim']) ?> • Semua kebutuhan sarana kampus, kini dalam genggaman.</p>
        <div class="aktif"><svg><use href="#grad" /></svg>Mahasiswa aktif</div>
      </section>
      <section class="stats">
        <div class="stat"><svg><use href="#doc" /></svg><b><?= $cP['Semua'] ?></b><span>Total pengaduan saya</span></div>
        <div class="stat"><svg><use href="#clk" /></svg><b><?= $cP['Menunggu'] + $cP['Sedang diproses'] ?></b><span>Pengaduan diproses</span></div>
        <div class="stat"><svg><use href="#chk" /></svg><b><?= $cP['Selesai'] ?></b><span>Pengaduan selesai</span></div>
        <div class="stat"><svg><use href="#wal" /></svg><b><?= h(rupiah($cL['denda'])) ?></b><span>Total denda alat</span></div>
      </section>

      <div class="sec-h"><h3>Pengaduan terbaru</h3><a href="pengaduan.php">Lihat semua →</a></div>
      <?php if ($pengaduan): $tg = Tanggapan::byPengaduan((int)$pengaduan['id_pengaduan'])[0] ?? null; ?>
      <article class="item" style="margin-bottom:22px">
        <div class="card-top"><small class="k">PENGADUAN #<?= (int)$pengaduan['id_pengaduan'] ?></small><?= pwa_badge_pengaduan($pengaduan['status_pengaduan'], true) ?></div>
        <h4 style="font-size:15px;margin:12px 0 6px"><?= h(pwa_judul($pengaduan['deskripsi_pengaduan'])) ?></h4>
        <p class="j" style="margin-bottom:14px"><?= h(tgl($pengaduan['tanggal'])) ?></p>
        <div class="info"><svg><use href="#info" /></svg><span><?= $tg ? 'Petugas: ' . h($tg['isi']) : 'Laporan Anda telah kami terima dan menunggu tindak lanjut petugas.' ?></span></div>
        <a class="btnline" href="pengaduan-detail.php?id=<?= (int)$pengaduan['id_pengaduan'] ?>">Lihat detail &amp; tanggapan →</a>
      </article>
      <?php else: ?><div class="tip" style="margin-bottom:22px"><svg><use href="#info" /></svg><span>Belum ada pengaduan. Ketuk <b>Pengaduan</b> untuk membuat laporan baru.</span></div><?php endif; ?>

      <div class="sec-h"><h3>Peminjaman alat terbaru</h3><a href="peminjaman.php">Lihat semua →</a></div>
      <?php if ($pinjam): ?>
      <article class="item">
        <div class="row">
          <div class="ico"><svg><use href="#<?= pwa_ikon($pinjam['nama_peminjaman']) ?>" /></svg></div>
          <div><h4><?= h($pinjam['nama_peminjaman']) ?></h4><span class="j"><?= (int)$pinjam['jumlah'] ?> unit • <?= h(tgl($pinjam['tgl_peminjaman'], true)) ?> – <?= h(tgl($pinjam['batas_kembali'], true)) ?></span></div>
        </div>
        <?= pwa_badge_pinjam($pinjam['status_peminjaman']) ?>
        <?php if ((int)$pinjam['denda'] > 0): ?><div class="info red"><svg><use href="#wal" /></svg><span>Denda <?= h(rupiah($pinjam['denda'])) ?>. Periksa rincian pengembalian dan kondisi alat.</span></div><?php endif; ?>
        <a class="btnline" href="peminjaman-detail.php?id=<?= (int)$pinjam['id_peminjaman'] ?>">Lihat detail →</a>
      </article>
      <?php else: ?><div class="tip"><svg><use href="#info" /></svg><span>Belum ada peminjaman. Ketuk <b>Peminjaman</b> untuk meminjam alat.</span></div><?php endif; ?>
      <p class="copy">© <?= date('Y') ?> Meta Sarpras • Politeknik Meta Industri Cikarang</p>
    </main>
<?php pwa_nav('beranda');
