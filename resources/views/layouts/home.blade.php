<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Nestly — Hostel Complaint Tracker')</title>
<meta name="description" content="@yield('meta_description', 'Nestly helps university hostel students submit and track complaints efficiently.')">
<meta name="theme-color" content="#f4f1ea">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root{
  --bg:#f4f1ea; --bg2:#ece7da; --card:#fcfaf5; --line:#e1dac8;
  --ink:#26312d; --ink2:#55615b; --mute:#7d887f;
  --sage:#5c8a73; --deep:#2f5444; --sagebg:#dfeadf;
  --sky:#6f9fb2; --skybg:#dcebf0; --clay:#c48d69; --claybg:#f4e4d6; --gold:#cfa650; --goldbg:#f5ebcf;
  --danger:#a8503a; --dangerbg:#f7e3da;
  --shadow:0 22px 44px -28px rgba(38,49,45,.38);
  --serif:'Fraunces',Georgia,serif; --sans:'Plus Jakarta Sans',system-ui,sans-serif;
}
*,*::before,*::after{box-sizing:border-box}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;overflow-x:hidden}
a{color:inherit;text-decoration:none}
h1,h2,h3,h4,p,ul{margin:0}
ul{padding:0;list-style:none}
button{font-family:inherit}
.wrap{max-width:1100px;margin:0 auto;padding:0 clamp(1rem,4vw,2.5rem)}

/* NAV */
nav#nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:.9rem 0;transition:background .3s,box-shadow .3s}
nav#nav.scrolled{background:rgba(244,241,234,.92);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);box-shadow:0 1px 0 var(--line)}
.nav-inner{display:flex;align-items:center;justify-content:space-between;gap:1rem}
.logo{display:inline-flex;align-items:center;gap:.65rem;font:600 1.35rem var(--serif)}
.logo-mark{width:36px;height:36px;border-radius:11px;background:var(--deep);color:#fff;display:grid;place-items:center;font-size:.95rem}
.nav-links{display:flex;align-items:center;gap:.4rem}
.nav-links a{padding:.55rem 1rem;border-radius:999px;font-size:.9rem;font-weight:600;color:var(--ink2);transition:background .2s,color .2s}
.nav-links a:hover{background:var(--bg2);color:var(--ink)}
.btn,.btn-white,.btn-ghost-w,.btn-solid{display:inline-flex;align-items:center;justify-content:center;gap:.55rem;padding:.75rem 1.4rem;border-radius:999px;font:600 .92rem var(--sans);border:1.5px solid transparent;cursor:pointer;transition:transform .25s,background .25s,border-color .25s,box-shadow .25s;white-space:nowrap}
.btn:hover{transform:translateY(-2px)}
.btn-solid{background:var(--deep);color:#fff;box-shadow:0 12px 26px -14px rgba(47,84,68,.7)}
.btn-solid:hover{background:#264537}
.btn-white{background:#fff;color:var(--deep)}
.btn-ghost-w{background:transparent;border-color:rgba(255,255,255,.5);color:#fff}
.btn-ghost-w:hover{background:rgba(255,255,255,.12)}
.btn-line{background:transparent;border-color:var(--line);color:var(--ink)}
.btn-line:hover{background:var(--card);border-color:var(--sage)}
.burger{width:42px;height:42px;border:1px solid var(--line);border-radius:12px;background:var(--card);color:var(--ink);cursor:pointer;font-size:1rem;display:none}
.mmenu{display:none}

/* SECTIONS */
section{padding:clamp(4rem,8vw,7rem) 0}
.head{margin-bottom:clamp(2rem,4vw,3.5rem)}
.head h2{font:600 clamp(2rem,4vw,2.8rem)/1.15 var(--serif);letter-spacing:-.02em;margin-bottom:.6rem}
.head p{color:var(--ink2);max-width:540px;font-size:1.05rem}
.eyebrow{font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--sage);display:block;margin-bottom:.5rem}

/* HERO */
#hero{padding:clamp(7rem,12vw,10rem) 0 clamp(4rem,7vw,6rem);background:linear-gradient(160deg,var(--deep) 0%,#3d6b57 60%,#4a7a65 100%);color:#fff;position:relative;overflow:hidden}
#hero::before{content:'';position:absolute;width:700px;height:700px;border-radius:50%;background:rgba(255,255,255,.05);top:-300px;right:-200px;pointer-events:none}
#hero::after{content:'';position:absolute;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.04);bottom:-200px;left:-100px;pointer-events:none}
#hero>.wrap{position:relative;display:grid;grid-template-columns:1.1fr 1fr;gap:3rem;align-items:center}
.hero-text .eyebrow{color:#f1dfa8}
.hero-text h1{font:600 clamp(2.4rem,5vw,3.5rem)/1.1 var(--serif);letter-spacing:-.025em;margin:.6rem 0 1.1rem}
.hero-text p{color:rgba(255,255,255,.82);font-size:1.08rem;max-width:520px;line-height:1.65}
.cta-row{display:flex;flex-wrap:wrap;gap:.8rem;margin-top:1.8rem}
.hero-stats{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.hstat{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:22px;padding:1.4rem;backdrop-filter:blur(8px)}
.hstat b{display:block;font:600 2.4rem/1 var(--serif);color:#f1dfa8}
.hstat span{font-size:.85rem;color:rgba(255,255,255,.75);margin-top:.25rem;display:block}

/* HOW IT WORKS */
.steps-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem}
.step{background:var(--card);border:1px solid var(--line);border-radius:24px;padding:1.6rem;transition:transform .3s,box-shadow .3s}
.step:hover{transform:translateY(-5px);box-shadow:var(--shadow)}
.step .d{width:52px;height:52px;border-radius:16px;background:var(--sagebg);color:var(--deep);display:grid;place-items:center;font-size:1.2rem;margin-bottom:1.1rem}
.step h3{font:600 1.1rem var(--serif);margin-bottom:.4rem}
.step p{color:var(--ink2);font-size:.9rem}

/* BENTO */
.bento{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem}
.cell{background:var(--card);border:1px solid var(--line);border-radius:24px;padding:1.6rem;transition:transform .3s,box-shadow .3s}
.cell:hover{transform:translateY(-4px);box-shadow:var(--shadow)}
.cell.s4{grid-column:span 2}
.cell.s2{grid-column:span 2}
.cell.tint-g{background:linear-gradient(135deg,var(--deep),#3d6b57);color:#fff;border:0}
.cell.tint-g .ic{background:#fff;color:var(--deep)}
.cell.tint-g h3{color:#fff}
.cell.tint-g p{color:rgba(255,255,255,.8)}
.cell.tint-d{background:linear-gradient(135deg,#1e3d30,#2f5444);color:#fff;border:0}
.cell.tint-d .ic{background:rgba(255,255,255,.15);color:#f1dfa8}
.cell.tint-d h3{color:#fff}
.cell.tint-d p{color:rgba(255,255,255,.75)}
.cell .ic{width:50px;height:50px;border-radius:14px;background:var(--sagebg);color:var(--deep);display:grid;place-items:center;font-size:1.2rem;margin-bottom:1.1rem}
.cell h3{font:600 1.1rem var(--serif);margin-bottom:.4rem}
.cell p{color:var(--ink2);font-size:.9rem}
.meter{margin-top:1rem;display:grid;gap:.5rem;font-size:.8rem;color:rgba(255,255,255,.9)}
.meter div{display:flex;align-items:center;gap:.6rem}
.meter span{flex:1;background:rgba(255,255,255,.15);border-radius:999px;height:6px;overflow:hidden}
.meter span i{display:block;height:100%;background:#f1dfa8;border-radius:999px;width:var(--w);transition:width 1.4s cubic-bezier(.2,.8,.2,1)}

/* ROLES */
.roles-tabs{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem}
.tab{padding:.6rem 1.2rem;border-radius:999px;border:1.5px solid var(--line);font-size:.88rem;font-weight:600;color:var(--ink2);cursor:pointer;background:transparent;transition:all .25s}
.tab.on{background:var(--deep);border-color:var(--deep);color:#fff}
.panel-r{display:none}
.panel-r.on{display:block}

/* FAQ */
.faq-grid{display:grid;grid-template-columns:1fr 1.8fr;gap:3rem;align-items:start}
.faq{display:grid;gap:.6rem}
details{background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;transition:box-shadow .25s}
details[open]{box-shadow:var(--shadow)}
summary{padding:1.1rem 1.3rem;font-weight:600;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;gap.8rem}
summary::after{content:'+';font-size:1.3rem;color:var(--sage);flex-shrink:0;transition:transform .3s}
details[open] summary::after{transform:rotate(45deg)}
details p{padding:.2rem 1.3rem 1.2rem;color:var(--ink2);font-size:.93rem}

/* CTA */
.cta{background:linear-gradient(120deg,var(--deep),#3d6b57);color:#fff;border-radius:32px;padding:clamp(2.5rem,5vw,4rem) clamp(1.5rem,4vw,3rem);text-align:center;position:relative;overflow:hidden;box-shadow:var(--shadow)}
.cta::before{content:'';position:absolute;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.06);top:-200px;right:-100px}
.cta>*{position:relative}
.cta h2{font:600 clamp(1.8rem,3.5vw,2.6rem)/1.15 var(--serif);letter-spacing:-.02em;margin-bottom:.7rem}
.cta p{color:rgba(255,255,255,.8);font-size:1.05rem;margin-bottom:1.8rem}
.cta-row{display:flex;flex-wrap:wrap;gap:.8rem;justify-content:center}

/* FOOTER */
footer{background:var(--bg2);border-top:1px solid var(--line);padding:clamp(2.5rem,5vw,4rem) 0 2rem}
.foot{display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:2rem;margin-bottom:2rem}
.foot .logo{color:var(--ink)}
.foot p{color:var(--ink2);font-size:.88rem;margin-top:.6rem;max-width:260px}
.foot h4{font-size:.74rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mute);font-weight:700;margin-bottom:.8rem}
.foot ul{display:grid;gap:.4rem}
.foot ul li a{color:var(--ink2);font-size:.9rem;transition:color .2s}
.foot ul li a:hover{color:var(--deep)}
.copy{display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding-top:1.5rem;border-top:1px solid var(--line);font-size:.82rem;color:var(--mute)}

/* Reveal animations */
.rv{opacity:0;transform:translateY(28px);transition:opacity .7s,transform .7s}
.rv.d1{transition-delay:.12s}.rv.d2{transition-delay:.22s}.rv.d3{transition-delay:.32s}
.rv.in{opacity:1;transform:none}

@media(max-width:900px){
  #hero>.wrap{grid-template-columns:1fr}
  .hero-stats{display:none}
  .bento{grid-template-columns:1fr 1fr}
  .cell.s4,.cell.s2{grid-column:span 2}
  .faq-grid{grid-template-columns:1fr}
  .foot{grid-template-columns:1fr 1fr}
  .burger{display:flex;align-items:center;justify-content:center}
  .nav-links{display:none}
  .mmenu{display:none;position:fixed;inset:0;top:64px;background:rgba(244,241,234,.97);backdrop-filter:blur(16px);padding:2rem;z-index:90;flex-direction:column;gap:.5rem}
  .mmenu.open{display:flex}
  .mmenu a{padding:.9rem 1rem;border-radius:14px;font-weight:600;font-size:1rem;color:var(--ink)}
  .mmenu a:hover{background:var(--bg2)}
}
@media(max-width:600px){
  .bento{grid-template-columns:1fr}
  .cell.s4,.cell.s2{grid-column:span 1}
  .cta-row{flex-direction:column;align-items:center}
  .steps-list{grid-template-columns:1fr}
  .foot{grid-template-columns:1fr}
}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
</style>
@stack('head')
</head>
<body>

<nav id="nav">
  <div class="wrap nav-inner">
    <a href="{{ route('home') }}" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a>
    <div class="nav-links">
      <a href="#how">How it works</a>
      <a href="#features">Features</a>
      <a href="#faq">FAQ</a>
      @guest
        <a href="{{ route('login') }}">Sign in</a>
        <a href="{{ route('register') }}" class="btn btn-solid" style="padding:.6rem 1.2rem">Get started</a>
      @else
        <a href="{{ route('dashboard') }}" class="btn btn-solid" style="padding:.6rem 1.2rem"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
      @endguest
    </div>
    <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false"><i class="fa-solid fa-bars"></i></button>
  </div>
  <div class="mmenu wrap" id="mmenu">
    <a href="#how">How it works</a>
    <a href="#features">Features</a>
    <a href="#faq">FAQ</a>
    @guest
      <a href="{{ route('login') }}">Sign in</a>
      <a href="{{ route('register') }}">Create account</a>
    @else
      <a href="{{ route('dashboard') }}">My dashboard</a>
    @endguest
  </div>
</nav>

@yield('content')

<footer>
  <div class="wrap">
    <div class="foot">
      <div>
        <a href="#top" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a>
        <p>Hostel complaint tracking and resolution for modern campuses.</p>
      </div>
      <div>
        <h4>Explore</h4>
        <ul><li><a href="#how">How it works</a></li><li><a href="#features">Features</a></li><li><a href="#faq">FAQ</a></li></ul>
      </div>
      <div>
        <h4>Account</h4>
        <ul>
          @guest
            <li><a href="{{ route('login') }}">Sign in</a></li>
            <li><a href="{{ route('register') }}">Create account</a></li>
          @else
            <li><a href="{{ route('dashboard') }}">My dashboard</a></li>
          @endguest
        </ul>
      </div>
    </div>
    <div class="copy"><span>&copy; <span id="yr"></span> Nestly. All rights reserved.</span><span>Built for better hostel living.</span></div>
  </div>
</footer>

<script>
(function(){
  var nav=document.getElementById('nav');
  function onScroll(){nav.classList.toggle('scrolled',window.scrollY>10)}
  onScroll();window.addEventListener('scroll',onScroll,{passive:true});
  document.getElementById('yr').textContent=new Date().getFullYear();
  var b=document.getElementById('burger'),m=document.getElementById('mmenu');
  b.addEventListener('click',function(){
    var o=m.classList.toggle('open');
    b.setAttribute('aria-expanded',o);
    b.innerHTML=o?'<i class="fa-solid fa-xmark"></i>':'<i class="fa-solid fa-bars"></i>';
  });
  m.querySelectorAll('a').forEach(function(a){a.addEventListener('click',function(){m.classList.remove('open');b.innerHTML='<i class="fa-solid fa-bars"></i>';})});
  var rv=document.querySelectorAll('.rv');
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}})},{threshold:.15});
    rv.forEach(function(el){io.observe(el)});
  }else{rv.forEach(function(el){el.classList.add('in')})}
  var reduce=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.querySelectorAll('[data-count]').forEach(function(el){
    var t=parseInt(el.getAttribute('data-count'),10)||0;
    if(reduce||!t||!('IntersectionObserver' in window)){el.textContent=t.toLocaleString();return}
    var done=false;
    new IntersectionObserver(function(es,o){
      if(!es[0].isIntersecting||done)return;done=true;o.disconnect();
      var s=performance.now(),d=1500;
      (function tick(n){var p=Math.min((n-s)/d,1);el.textContent=Math.round(t*(1-Math.pow(1-p,3))).toLocaleString();if(p<1)requestAnimationFrame(tick)})(s);
    },{threshold:.6}).observe(el);
  });
  var tabs=document.querySelectorAll('.tab');
  tabs.forEach(function(t){t.addEventListener('click',function(){
    tabs.forEach(function(x){x.classList.remove('on')});
    document.querySelectorAll('.panel-r').forEach(function(p){p.classList.remove('on')});
    t.classList.add('on');
    var el=document.getElementById('p-'+t.dataset.t);
    if(el)el.classList.add('on');
  })});
  // init meter bars after reveal
  setTimeout(function(){document.querySelectorAll('.meter span i').forEach(function(i){i.style.width=i.style.getPropertyValue('--w')||i.getAttribute('style').match(/--w:([^;]+)/)?.[1]||'0'})},400);
})();
</script>
@stack('scripts')
</body>
</html>
