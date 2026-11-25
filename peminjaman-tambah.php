<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require();
$err = ''; $f = ['nama' => '', 'jumlah' => 1, 'pinjam' => date('Y-m-d'), 'batas' => date('Y-m-d', strtotime('+3 days')), 'keperluan' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['nama', 'pinjam', 'batas', 'keperluan'] as $k) $f[$k] = trim($_POST[$k] ?? '');
    $f['jumlah'] = (int)($_POST['jumlah'] ?? 0);
    try {
        if (!csrf_ok($_POST['csrf'] ?? null)) throw new RuntimeException('Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.');
        if ($f['nama'] === '' || mb_strlen($f['nama']) > 150) throw new RuntimeException('Nama alat wajib diisi (maksimal 150 karakter).');
        if ($f['jumlah'] < 1 || $f['jumlah'] > 999) throw new RuntimeException('Jumlah alat minimal 1 unit.');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['pinjam']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['batas'])) throw new RuntimeException('Tanggal tidak valid.');
        if ($f['batas'] < $f['pinjam']) throw new RuntimeException('Batas pengembalian tidak boleh sebelum tanggal peminjaman.');
        if ($f['keperluan'] === '') throw new RuntimeException('Keperluan penggunaan wajib diisi.');
        $foto = upload_foto('foto');
        if (!$foto) throw new RuntimeException('Foto barang sebelum dipinjam wajib diunggah.');
        $id = Peminjaman::create((int)$u['id_user'], $f['nama'], $f['jumlah'], $f['pinjam'], $f['batas'], $f['keperluan'], $foto);
        redirect('peminjaman-detail.php?id=' . $id);
    } catch (RuntimeException $e) { $err = $e->getMessage(); }
}
pwa_open('Tambah Peminjaman'); pwa_header($u, 'Tambah peminjaman', 'back', '', 'peminjaman.php'); ?>
    <main>
      <form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
        <h2 class="title">Pinjam alat praktikum</h2>
        <p class="sub">Lengkapi kebutuhan alat dan jadwal peminjaman sebelum mengajukan.</p>
        <?php if ($err): ?><div class="info red error-box"><svg><use href="#info" /></svg><span><?= h($err) ?></span></div><?php endif; ?>
        <div class="card">
          <h3>Alat yang dipinjam</h3>
          <p class="hint" style="margin-top:-4px">Tanda * menunjukkan kolom wajib diisi.</p>
          <div class="field"><label for="nama">Nama alat / keperluan praktikum *</label>
            <input class="inp" id="nama" name="nama" list="alat" value="<?= h($f['nama']) ?>" required autocomplete="off" maxlength="150">
            <datalist id="alat"><option>sound</option><option>tang kombinasi</option><option>Digital Multimeter Dekko DM-133D</option></datalist>
            <p class="hint" style="margin:8px 0 0">Pilih atau tuliskan nama alat yang akan dipinjam.</p></div>
          <div class="field"><label for="jumlah">Jumlah alat *</label><input class="inp" id="jumlah" name="jumlah" type="number" min="1" value="<?= (int)$f['jumlah'] ?>" required>
            <p class="hint" style="margin:8px 0 0">Jumlah dalam satuan unit.</p></div>
        </div>
        <div class="card">
          <h3>Jadwal &amp; keperluan</h3>
          <div class="field"><label for="pinjam">Tanggal peminjaman *</label><input class="inp" id="pinjam" name="pinjam" type="date" value="<?= h($f['pinjam']) ?>" required></div>
          <div class="field"><label for="batas">Batas pengembalian (wajib) *</label><input class="inp" id="batas" name="batas" type="date" value="<?= h($f['batas']) ?>" required></div>
          <div class="field"><label for="keperluan">Keperluan penggunaan alat *</label><textarea class="inp" id="keperluan" name="keperluan" required placeholder="Contoh: Untuk kegiatan praktikum di ruang laboratorium."><?= h($f['keperluan']) ?></textarea></div>
        </div>
        <div class="card">
          <h3>Dokumentasi kondisi awal</h3>
          <label for="foto">Foto barang sebelum dipinjam *</label>
          <label class="upload" for="foto" style="font-weight:400;margin:0;cursor:pointer;display:block"><svg><use href="#cam" /></svg><p>Ambil atau pilih foto</p><small>JPG, PNG • Maksimal 5 MB</small><span class="pick">Pilih foto</span></label>
          <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" required style="width:100%;margin-top:12px;font-size:12px">
          <p class="hint" style="margin:14px 0 0">Foto kondisi alat sebelum dipinjam menjadi bukti saat pengembalian. Pastikan seluruh bagian terlihat jelas.</p>
        </div>
        <div class="notice"><svg><use href="#shield" /></svg><span>Dengan mengajukan peminjaman, saya bersedia menjaga alat dan mengembalikannya sesuai batas waktu.</span></div>
        <button class="btn main" type="submit">Ajukan peminjaman alat</button>
        <a class="btn" href="peminjaman.php">Batal</a>
      </form>
    </main>
<?php pwa_nav('peminjaman');
