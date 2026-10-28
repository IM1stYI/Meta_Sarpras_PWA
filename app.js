try{if(sessionStorage.getItem('mhs')===null)location.replace('login.html')}catch(e){}
const I={home:'<path d="M3 11l9-8 9 8v9a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/>',flag:'<path d="M5 21V4m0 1h12l-2 4 2 4H5"/>',box:'<rect x="4" y="4" width="16" height="16" rx="3"/><rect x="9" y="9" width="6" height="6" rx="1"/>'};
const svg=(p,s=18)=>`<svg width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
const BACK=svg('<path d="M19 12H5m6-7l-7 7 7 7"/>',22);
document.body.insertAdjacentHTML('beforeend',`<nav class="bn"><a href="#" data-t="Beranda">${svg(I.home)}Beranda</a><a href="#" data-t="Pengaduan">${svg(I.flag)}Pengaduan</a><a href="daftar.html" class="on">${svg(I.box)}Peminjaman</a></nav><div class="toast" id="toast"></div>`);
document.querySelectorAll('nav.bn a[data-t]').forEach(a=>a.onclick=e=>{e.preventDefault();toast('Halaman '+a.dataset.t+' belum tersedia')});
function toast(m){const t=document.getElementById('toast');t.textContent=m;t.classList.add('show');clearTimeout(t._t);t._t=setTimeout(()=>t.classList.remove('show'),2200)}
if('serviceWorker'in navigator&&location.protocol.startsWith('http'))navigator.serviceWorker.register('sw.js').catch(()=>{});

document.querySelectorAll('.avatar').forEach(v=>{v.style.cursor='pointer';v.title='Keluar';v.onclick=()=>{if(confirm('Keluar dari akun?')){try{sessionStorage.clear()}catch(e){}location.replace('login.html')}}});
