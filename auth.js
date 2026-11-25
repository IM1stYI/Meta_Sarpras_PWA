/* PWA: konfirmasi keluar + pasang service worker.
   (Autentikasi kini dilakukan di server lewat PHP session: login.php / logout.php) */
document.addEventListener('DOMContentLoaded', function () {
  var a = document.querySelector('[data-logout]');
  if (a) a.addEventListener('click', function (e) {
    if (!confirm('Keluar dari akun?')) e.preventDefault();
  });
});
if ('serviceWorker' in navigator && location.protocol.indexOf('http') === 0) {
  navigator.serviceWorker.register('sw.js').catch(function () {});
}
