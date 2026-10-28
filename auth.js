/* Autentikasi dummy (hanya untuk prototipe). NIM: 2425001 | Password: mahasiswa */
(function(){
  var K='meta_sarpras_user', USER={nim:'2425001',nama:'Amril Razy',prodi:'Teknik Industri'}, PASS='mahasiswa';
  var page=location.pathname.split('/').pop(), login=page==='login.html', u=null;
  try{u=JSON.parse(sessionStorage.getItem(K)||localStorage.getItem(K))}catch(e){}
  if(login&&u){location.replace('beranda.html');return}
  if(!login&&!u){location.replace('login.html');return}
  document.addEventListener('DOMContentLoaded',function(){
    if(!login){
      var a=document.querySelector('[data-logout]');
      a&&a.addEventListener('click',function(e){e.preventDefault();
        if(confirm('Keluar dari akun '+u.nama+'?')){sessionStorage.removeItem(K);localStorage.removeItem(K);location.replace('login.html')}});
      return}
    var f=document.getElementById('login'),err=document.getElementById('err'),pw=document.getElementById('pw'),eye=document.getElementById('eye');
    eye.addEventListener('click',function(){var h=pw.type==='password';pw.type=h?'text':'password';eye.setAttribute('aria-label',h?'Sembunyikan password':'Tampilkan password')});
    f.addEventListener('submit',function(e){e.preventDefault();
      var n=document.getElementById('nim').value.trim(),p=pw.value;
      if(n!==USER.nim||p!==PASS){err.textContent=!n||!p?'NIM dan password wajib diisi.':'NIM atau password salah. Silakan coba lagi.';err.classList.add('on');return}
      (document.getElementById('rem').checked?localStorage:sessionStorage).setItem(K,JSON.stringify(USER));
      location.replace('beranda.html')});
  });
})();

/* PWA: pasang manifest & service worker di semua halaman */
(function(){
  if(!document.querySelector('link[rel="manifest"]')){var l=document.createElement('link');l.rel='manifest';l.href='manifest.json';document.head.appendChild(l)}
  if('serviceWorker' in navigator && location.protocol.indexOf('http')===0) navigator.serviceWorker.register('sw.js').catch(function(){});
})();
