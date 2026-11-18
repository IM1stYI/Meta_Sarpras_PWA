<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require();
$p = Peminjaman::findMilik((int)($_GET['id'] ?? 0), (int)$u['id_mahasiswa']);
if (!$p) redirect('peminjaman.php');
$st = $p['status_peminjaman']; $admin = $p['nama_admin'] ?? 'Administrator Sarpras';
$foto = foto_url($p['foto_fisik_barang']);
$telat = $p['tgl_pengembalian_aktual'] ? max(0, selisih_hari($p['tgl_pengembalian_aktual'], $p['batas_kembali'])) : 0;
// riwayat (terbaru di atas)
$ev = [];
if ($st === 'Telah Dikembalikan') $ev[] = ['Pengembalian diverifikasi', $p['tgl_pengembalian_aktual'], $admin,
    'Alat telah diterima dalam kondisi ' . mb_strtolower($p['kondisi_peminjaman'] ?: 'baik') . '.' . ((int)$p['denda'] > 0 ? ' Terdapat keterlambatan ' . $telat . ' hari; denda ' . rupiah($p['denda']) . '.' : '')];
if (in_array($st, ['Disetujui', 'Dipinjam', 'Telah Dikembalikan'], true)) $ev[] = ['Peminjaman disetujui', $p['tgl_peminjaman'], $admin, 'Peminjaman telah disetujui. Harap menjaga kondisi barang dan mengembalikannya tepat waktu.'];
if ($st === 'Ditolak') $ev[] = ['Peminjaman ditolak', $p['tgl_peminjaman'], $admin, $p['catatan_admin'] ?: 'Pengajuan peminjaman tidak dapat disetujui.'];
$ev[] = ['Peminjaman diajukan', $p['tgl_peminjaman'], $u['nama_mahasiswa'], 'Pengajuan ' . (int)$p['jumlah'] . ' unit ' . $p['nama_peminjaman'] . ' untuk kegiatan praktikum telah diterima.'];
pwa_open('Detail Peminjaman'); pwa_header($u, 'Detail peminjaman', 'back', '', 'peminjaman.php'); ?>
    <main>
      <div class="head"><span>PEMINJAMAN #<?= (int)$p['id_peminjaman'] ?></span><?= pwa_badge_pinjam($st) ?></div>
      <h2 class="title"><?= h($p['nama_peminjaman']) ?></h2>
      <p class="sub">Informasi alat, pengembalian, dan riwayat tanggapan petugas.</p>
      <div class="card">
        <h3>Informasi peminjaman</h3>
        <div class="gr"><div><small>Jumlah alat</small><b><?= (int)$p['jumlah'] ?> unit</b></div><div><small>Peminjam</small><b><?= h($u['nama_mahasiswa']) ?></b></div></div>
        <div class="hr"></div>
        <div class="gr"><div><small>Tanggal pinjam</small><b><?= h(tgl($p['tgl_peminjaman'])) ?></b></div><div><small>Batas kembali</small><b><?= h(tgl($p['batas_kembali'])) ?></b></div></div>
        <div style="margin-top:16px"><span class="lbl">Keperluan penggunaan</span><span class="val"><?= nl2br(h($p['keterangan'] ?: '-')) ?></span></div>
      </div>
      <div class="card">
        <h3>Kondisi &amp; pengembalian</h3>
        <?php if ($st === 'Telah Dikembalikan'): ?>
        <div class="info"><svg><use href="#chk" /></svg><span>Pengembalian telah diverifikasi oleh <?= h($admin) ?>.</span></div>
        <div class="gr"><div><small>Tanggal dikembalikan</small><b><?= h(tgl($p['tgl_pengembalian_aktual'])) ?></b></div><div><small>Kondisi akhir alat</small><b><?= h($p['kondisi_peminjaman'] ?: '-') ?></b></div></div>
        <?php if ($p['catatan_admin']): ?><div style="margin-top:16px"><span class="lbl">Catatan pengembalian</span><span class="val"><?= nl2br(h($p['catatan_admin'])) ?></span></div><?php endif; ?>
        <div class="hr"></div>
        <?php elseif ($st === 'Ditolak'): ?>
        <div class="info red"><svg><use href="#info" /></svg><span>Pengajuan ditolak<?= $p['catatan_admin'] ? ': ' . h($p['catatan_admin']) : '.' ?></span></div>
        <?php else: ?>
        <div class="info"><svg><use href="#info" /></svg><span><?= $st === 'Menunggu Konfirmasi' ? 'Pengajuan Anda sedang menunggu konfirmasi petugas.' : 'Alat sedang dipinjam. Kembalikan sebelum ' . h(tgl($p['batas_kembali'])) . '.' ?></span></div>
        <?php endif; ?>
        <div class="photos"><div>
          <div class="img"><?php if ($foto): ?><img src="<?= h($foto) ?>" alt="Foto kondisi awal"><?php else: ?><i></i><?php endif; ?></div>
          <small>Foto kondisi awal</small><?php if ($p['kondisi_peminjaman']): ?><span class="tag"><?= h($p['kondisi_peminjaman']) ?></span><?php endif; ?>
        </div></div>
      </div>
      <?php if ((int)$p['denda'] > 0): ?>
      <div class="fine">
        <div class="t"><svg><use href="#wal" /></svg>Denda keterlambatan</div>
        <h2><?= h(rupiah($p['denda'])) ?></h2>
        <p><?= $telat >= 3 ? 'Terlambat ' . $telat . ' hari × Rp10.000 per hari.' : h($p['keterangan_denda'] ?: 'Denda keterlambatan.') ?></p>
        <p style="margin:0">Silakan konfirmasi pembayaran kepada petugas sarpras.</p>
      </div>
      <?php endif; ?>
      <div class="card tl">
        <h3>Riwayat &amp; tanggapan <span><?= count($ev) ?> aktivitas</span></h3>
        <?php foreach ($ev as $e): ?>
        <div class="ev"><div class="dot"></div><div><h5><?= h($e[0]) ?></h5><small><?= h(tgl($e[1])) ?> • <?= h($e[2]) ?></small><p><?= h($e[3]) ?></p></div></div>
        <?php endforeach; ?>
      </div>
      <a class="btn" href="peminjaman.php">Kembali ke daftar peminjaman</a>
    </main>
<?php pwa_nav('peminjaman');
