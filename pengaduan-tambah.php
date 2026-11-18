<?php
require __DIR__ . '/../app/bootstrap.php'; require __DIR__ . '/partials/layout.php';
$u = mhs_require();
$err = ''; $f = ['tanggal' => date('Y-m-d'), 'deskripsi' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f['tanggal'] = $_POST['tanggal'] ?? ''; $f['deskripsi'] = trim($_POST['deskripsi'] ?? '');
    try {
        if (!csrf_ok($_POST['csrf'] ?? null)) throw new RuntimeException('Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['tanggal'])) throw new RuntimeException('Tanggal pelaporan tidak valid.');
        if ($f['deskripsi'] === '' || mb_strlen($f['deskripsi']) > 1000) throw new RuntimeException('Deskripsi wajib diisi (maksimal 1000 karakter).');
        if (empty($_POST['setuju'])) throw new RuntimeException('Centang pernyataan persetujuan terlebih dahulu.');
        $foto = upload_foto('foto');
        if (!$foto) throw new RuntimeException('Foto kondisi kerusakan wajib diunggah.');
        $id = Pengaduan::create((int)$u['id_mahasiswa'], $f['deskripsi'], $foto, $f['tanggal']);
        redirect('pengaduan-detail.php?id=' . $id);
    } catch (RuntimeException $e) { $err = $e->getMessage(); }
}
pwa_open('Buat Pengaduan'); pwa_header($u, 'Buat pengaduan', 'back', '', 'pengaduan.php'); ?>
    <main>
      <form method="post" enctype="multipart/form-data"><?= csrf_field() ?>
        <h2 class="title">Ada fasilitas bermasalah?</h2>
        <p class="sub">Ceritakan kondisinya. Kami bantu teruskan ke petugas sarana prasarana.</p>
        <?php if ($err): ?><div class="info red error-box"><svg><use href="#info" /></svg><span><?= h($err) ?></span></div>
        <?php else: ?><div class="info" style="margin-bottom:20px"><svg><use href="#info" /></svg><span>Isi informasi dengan jelas agar petugas dapat menemukan masalahnya dengan lebih cepat.</span></div><?php endif; ?>
        <div class="card">
          <h3>Informasi pengaduan</h3>
          <p class="hint" style="margin-top:-4px">Tanda * menunjukkan bahwa wajib diisi.</p>
          <div class="field"><label for="tgl">Tanggal pelaporan *</label><input class="inp" id="tgl" name="tanggal" type="date" value="<?= h($f['tanggal']) ?>" required></div>
          <div class="field"><label for="desk">Deskripsi kerusakan / keluhan *</label><textarea class="inp" id="desk" name="deskripsi" maxlength="1000" required placeholder="Ceritakan kondisi fasilitas, lokasi, dan dampaknya..."><?= h($f['deskripsi']) ?></textarea>
            <p class="hint" style="margin:8px 0 0">Tuliskan kondisi, lokasi ruang, dan kendala yang dirasakan.</p></div>
          <div class="field"><label for="foto">Foto kondisi kerusakan *</label>
            <label class="upload" for="foto" style="font-weight:400;margin:0;cursor:pointer;display:block"><svg><use href="#cam" /></svg><p>Ambil atau pilih foto</p><small>JPG, PNG · Maksimal 5 MB</small><span class="pick">Pilih foto</span></label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" required style="width:100%;margin-top:12px;font-size:12px">
            <p class="hint" style="margin:10px 0 0">Pastikan kerusakan terlihat jelas. Hindari mengunggah informasi pribadi dalam foto.</p></div>
        </div>
        <label class="consent"><input type="checkbox" name="setuju" required><span>Laporan ini dikirim oleh saya. Saya bersedia dihubungi apabila diperlukan untuk penanganan fasilitas kampus.</span></label>
        <button class="btn main" type="submit">Kirim pengaduan sekarang</button>
        <a class="btn" href="pengaduan.php">Batal</a>
      </form>
    </main>
<?php pwa_nav('pengaduan');
