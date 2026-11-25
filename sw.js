// Service worker: data & halaman selalu diambil dari server (PHP + database).
// Hanya aset statis yang disimpan untuk mempercepat pemuatan.
const C = 'meta-sarpras-php-v1';
const ASET = ['style.css', 'logo.png', 'auth.js', 'manifest.json'];
self.addEventListener('install', e => { self.skipWaiting(); e.waitUntil(caches.open(C).then(c => Promise.all(ASET.map(u => c.add(u).catch(() => {}))))); });
self.addEventListener('activate', e => e.waitUntil(caches.keys().then(k => Promise.all(k.filter(x => x !== C).map(x => caches.delete(x)))).then(() => self.clients.claim())));
self.addEventListener('fetch', e => {
  const r = e.request;
  if (r.method !== 'GET') return;
  const url = new URL(r.url);
  if (ASET.some(a => url.pathname.endsWith('/' + a))) {
    e.respondWith(caches.match(r).then(m => m || fetch(r)));
  }
  // halaman PHP & foto upload: langsung ke jaringan (tidak di-cache) agar data selalu terbaru
});
