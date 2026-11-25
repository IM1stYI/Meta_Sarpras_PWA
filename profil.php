<?php
require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/partials/layout.php';

$u = mhs_require();
$id = (int)$u['id_mahasiswa'];
$err = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_ok($_POST['csrf'] ?? null)) {
            throw new RuntimeException('Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.');
        }
        $pwLama = (string)($_POST['pw_lama'] ?? '');
        $pwBaru = (string)($_POST['pw_baru'] ?? '');
        $pwKonf = (string)($_POST['pw_konf'] ?? '');

        if ($pwLama === '' || $pwBaru === '' || $pwKonf === '') {
            throw new RuntimeException('Semua kolom password wajib diisi.');
        }
        if (!password_verify($pwLama, $u['password'])) {
            throw new RuntimeException('Password saat ini (lama) tidak sesuai.');
        }
        if (mb_strlen($pwBaru) < 6) {
            throw new RuntimeException('Password baru minimal harus 6 karakter.');
        }
        if ($pwBaru !== $pwKonf) {
            throw new RuntimeException('Konfirmasi password baru tidak cocok.');
        }
        if ($pwBaru === $pwLama) {
            throw new RuntimeException('Password baru tidak boleh sama dengan password saat ini.');
        }

        Mahasiswa::resetPassword($id, $pwBaru);
        $u = Mahasiswa::find($id); // perbarui data user di memori
        $sukses = 'Password akun berhasil diperbarui! Gunakan password baru Anda saat login berikutnya.';
    } catch (RuntimeException $e) {
        $err = $e->getMessage();
    }
}

pwa_open('Profil Mahasiswa');
pwa_header($u, 'Profil & Keamanan', 'dark', 'AKUN MAHASISWA', 'beranda.php');
?>
    <main>
      <section class="welcome" style="margin-bottom:20px">
        <div style="display:flex;align-items:center;gap:16px">
          <div class="avatar" style="width:58px;height:58px;font-size:22px;border-width:3px"><?= h(inisial($u['nama_mahasiswa'])) ?></div>
          <div>
            <div class="eb">PORTAL MAHASISWA</div>
            <h2 style="font-size:18px;margin:2px 0 4px"><?= h($u['nama_mahasiswa']) ?></h2>
            <div class="aktif" style="margin-top:2px"><svg><use href="#grad" /></svg>Mahasiswa aktif</div>
          </div>
        </div>
      </section>

      <div class="card">
        <h3>👨‍🎓 Profil &amp; Keamanan Akun Mahasiswa</h3>

        <div class="info" style="margin-bottom:20px;line-height:1.55">
          <svg><use href="#info" /></svg>
          <span>Sesuai kebijakan akademik kampus, <b>NIM</b> dan <b>Nama Lengkap Mahasiswa</b> bersifat tetap dan terkunci. Mahasiswa hanya diizinkan untuk mengelola kata sandi (password). Untuk perbaikan data identitas, silakan hubungi Administrator Sarpras / BAAK.</span>
        </div>

        <div class="field">
          <label for="nim_mhs">Nomor Induk Mahasiswa (NIM)</label>
          <input class="inp" id="nim_mhs" type="text" value="<?= h($u['nim']) ?>" readonly disabled style="background:#edf1f7;color:#475569;cursor:not-allowed">
          <p class="hint">Terkunci • Dikelola langsung oleh Bagian Akademik &amp; Admin Sarpras.</p>
        </div>

        <div class="field">
          <label for="nama_mhs">Nama Lengkap Mahasiswa</label>
          <input class="inp" id="nama_mhs" type="text" value="<?= h($u['nama_mahasiswa']) ?>" readonly disabled style="background:#edf1f7;color:#475569;cursor:not-allowed">
          <p class="hint">Terkunci • Tidak dapat diubah oleh mahasiswa (data resmi identitas kampus).</p>
        </div>
      </div>

      <div class="card">
        <h3>🔐 Ganti Password Akun</h3>
        <p class="hint" style="margin-top:-4px;margin-bottom:16px">Masukkan password saat ini dan tentukan password baru yang aman.</p>

        <?php if ($err): ?>
        <div class="info red"><svg><use href="#info" /></svg><span><?= h($err) ?></span></div>
        <?php endif; ?>

        <?php if ($sukses): ?>
        <div class="info" style="background:#e6f9f0;color:#12805c;border:1px solid #b7ecd4"><svg><use href="#chk" /></svg><span><?= h($sukses) ?></span></div>
        <?php endif; ?>

        <form method="post" id="form-password"><?= csrf_field() ?>
          <div class="field">
            <label for="pw_lama">Password Saat Ini (Lama) <span style="color:var(--red)">*</span></label>
            <input class="inp" id="pw_lama" name="pw_lama" type="password" placeholder="Masukkan password saat ini" required autocomplete="current-password">
          </div>

          <div class="field">
            <label for="pw_baru">Password Baru <span style="color:var(--red)">*</span></label>
            <input class="inp" id="pw_baru" name="pw_baru" type="password" placeholder="Minimal 6 karakter" minlength="6" required autocomplete="new-password">
          </div>

          <div class="field">
            <label for="pw_konf">Konfirmasi Password Baru <span style="color:var(--red)">*</span></label>
            <input class="inp" id="pw_konf" name="pw_konf" type="password" placeholder="Ulangi password baru" minlength="6" required autocomplete="new-password">
          </div>

          <label class="consent" style="margin:14px 0 18px;display:flex;align-items:center;gap:8px;cursor:pointer">
            <input type="checkbox" id="show_pw" style="width:16px;height:16px;accent-color:var(--orange)">
            <span>Tampilkan karakter password</span>
          </label>

          <button class="btn main" type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px">
            <svg style="width:18px;height:18px"><use href="#key" /></svg>
            <span>Perbarui Password Akun →</span>
          </button>
        </form>
      </div>

      <div class="card" style="text-align:center">
        <h3 style="font-size:16px;margin-bottom:6px">Keluar dari Akun</h3>
        <p class="hint" style="margin-bottom:14px">Pastikan Anda keluar setelah menggunakan aplikasi jika menggunakan perangkat bersama.</p>
        <a class="btn" href="logout.php" data-logout style="color:var(--red);border:1px solid #fca5a5;background:#fff5f5;text-decoration:none;display:block">
          Keluar dari Akun (Logout)
        </a>
      </div>

      <p class="copy">© <?= date('Y') ?> Meta Sarpras • Politeknik Meta Industri Cikarang</p>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var chk = document.getElementById('show_pw');
        var inputs = ['pw_lama', 'pw_baru', 'pw_konf'].map(function (id) {
          return document.getElementById(id);
        });
        if (chk) {
          chk.addEventListener('change', function () {
            var type = chk.checked ? 'text' : 'password';
            inputs.forEach(function (inp) {
              if (inp) inp.type = type;
            });
          });
        }

        var form = document.getElementById('form-password');
        if (form) {
          form.addEventListener('submit', function (e) {
            var p1 = document.getElementById('pw_baru');
            var p2 = document.getElementById('pw_konf');
            if (p1 && p2 && p1.value !== p2.value) {
              e.preventDefault();
              alert('Konfirmasi password baru tidak cocok!');
              p2.focus();
            }
          });
        }
      });
    </script>
<?php pwa_nav('profil');
