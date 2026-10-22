<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Nestly') | Nestly</title>
<meta name="description" content="@yield('meta_description', 'Nestly — Hostel complaint tracker and resolution.')">
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
  --side:260px;
}
*,*::before,*::after{box-sizing:border-box}
html{-webkit-text-size-adjust:100%}
body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;overflow-x:hidden}
a{color:inherit;text-decoration:none}
h1,h2,h3,h4,p,ul{margin:0}
ul{padding:0;list-style:none}
button{font-family:inherit}

/* Layout */
.app{display:grid;grid-template-columns:var(--side) 1fr;min-height:100vh;min-height:100dvh}
.sidebar{position:sticky;top:0;height:100vh;height:100dvh;background:var(--deep);color:#fff;padding:1.5rem 1.1rem;display:flex;flex-direction:column;overflow:hidden;z-index:60}
.sidebar::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.05);top:-120px;right:-120px;pointer-events:none}
.sidebar>*{position:relative}
.logo{display:flex;align-items:center;gap:.65rem;font:600 1.4rem var(--serif);padding:0 .5rem;margin-bottom:2rem}
.logo-mark{width:38px;height:38px;border-radius:12px;background:rgba(255,255,255,.14);display:grid;place-items:center;font-size:1rem}
.nav-label{font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.5);padding:0 .8rem;margin-bottom:.6rem}
.nav a{display:flex;align-items:center;gap:.85rem;padding:.75rem .9rem;border-radius:14px;font-size:.94rem;font-weight:500;color:rgba(255,255,255,.78);transition:background .25s,color .25s,transform .25s;margin-bottom:.2rem}
.nav a i{width:20px;text-align:center}
.nav a:hover{background:rgba(255,255,255,.09);color:#fff;transform:translateX(3px)}
.nav a.on{background:rgba(255,255,255,.15);color:#fff}
.side-tip{margin-top:auto;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:18px;padding:1rem 1.1rem;font-size:.84rem;color:rgba(255,255,255,.8);margin-bottom:1rem}
.side-tip b{display:flex;align-items:center;gap:.5rem;color:#f1dfa8;margin-bottom:.3rem;font-size:.86rem}
.me{display:flex;align-items:center;gap:.8rem;padding:.7rem;border-radius:16px;background:rgba(255,255,255,.08)}
.avatar{width:40px;height:40px;border-radius:12px;background:#f1dfa8;color:var(--deep);display:grid;place-items:center;font:600 1.05rem var(--serif);flex-shrink:0}
.me .who{flex:1;min-width:0}
.me b{display:block;font-size:.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.me small{color:rgba(255,255,255,.6);font-size:.74rem}
.me button{width:36px;height:36px;border:0;border-radius:10px;background:transparent;color:rgba(255,255,255,.7);cursor:pointer;transition:background .2s,color .2s}
.me button:hover{background:rgba(255,255,255,.14);color:#fff}

.main{min-width:0;display:flex;flex-direction:column}
.topbar{display:none;position:sticky;top:0;z-index:40;align-items:center;justify-content:space-between;padding:.8rem 1rem;background:rgba(244,241,234,.9);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid var(--line)}
.topbar .logo{color:var(--ink);margin:0;padding:0;font-size:1.25rem}
.topbar .logo-mark{background:var(--deep);color:#fff;width:34px;height:34px}
.burger{width:42px;height:42px;border:1px solid var(--line);border-radius:12px;background:var(--card);color:var(--ink);cursor:pointer;font-size:1rem}
.scrim{position:fixed;inset:0;background:rgba(38,49,45,.45);opacity:0;pointer-events:none;transition:opacity .3s;z-index:55}
.scrim.show{opacity:1;pointer-events:auto}
.content{width:100%;max-width:1180px;margin:0 auto;padding:clamp(1.2rem,3vw,2.4rem) clamp(1rem,3vw,2.2rem) 3rem}

/* Banner */
.banner{position:relative;overflow:hidden;background:linear-gradient(120deg,var(--deep),#3d6b57);color:#fff;border-radius:28px;padding:clamp(1.4rem,3vw,2.2rem);display:flex;align-items:center;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;box-shadow:var(--shadow);animation:rise .7s ease both}
.banner::before,.banner::after{content:'';position:absolute;border-radius:50%;background:rgba(255,255,255,.07)}
.banner::before{width:300px;height:300px;top:-130px;right:-60px}
.banner::after{width:200px;height:200px;bottom:-100px;left:30%}
.banner>*{position:relative}
.b-left{display:flex;align-items:center;gap:1.2rem;min-width:0}
.b-icon{width:62px;height:62px;border-radius:20px;background:rgba(255,255,255,.14);display:grid;place-items:center;font-size:1.6rem;flex-shrink:0;animation:bob 5s ease-in-out infinite}
@keyframes bob{50%{transform:translateY(-6px) rotate(-4deg)}}
.date{font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.65);margin-bottom:.2rem}
.banner h1{font:600 clamp(1.4rem,3vw,2.1rem)/1.15 var(--serif);letter-spacing:-.01em}
.banner p{color:rgba(255,255,255,.8);font-size:.93rem;margin-top:.3rem}
.banner p strong{color:#f1dfa8;font-weight:600}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.6rem;padding:.85rem 1.5rem;border-radius:999px;font:600 .93rem var(--sans);border:1.5px solid transparent;cursor:pointer;transition:transform .25s,background .25s,box-shadow .25s,border-color .25s;white-space:nowrap}
.btn:hover{transform:translateY(-2px)}
.btn-light{background:#fcfaf5;color:var(--deep)}
.btn-solid{background:var(--deep);color:#fff;box-shadow:0 12px 26px -14px rgba(47,84,68,.8)}
.btn-solid:hover{background:#264537}
.btn-line{background:transparent;border-color:var(--line);color:var(--ink)}
.btn-line:hover{background:var(--card);border-color:var(--sage)}
.btn-gold{background:var(--goldbg);color:#82641a;border-color:#ead9a6}
.btn-gold:hover{background:#f2e4b8}
.btn-danger{background:var(--dangerbg);color:var(--danger);border-color:#e9c3b8}
.btn-danger:hover{background:#f3d0c5}
.btn-sm{padding:.5rem .95rem;font-size:.82rem}
@keyframes rise{from{opacity:0;transform:translateY(22px)}}

/* Flash */
.flash{display:flex;gap:.7rem;align-items:flex-start;padding:.85rem 1.1rem;border-radius:16px;margin-bottom:1rem;font-size:.9rem;background:var(--sagebg);color:var(--deep)}
.flash.error,.flash.danger{background:var(--dangerbg);color:var(--danger)}
.flash.warning{background:var(--goldbg);color:#6e5413}
.flash.success{background:var(--sagebg);color:var(--deep)}
.flash i{margin-top:.25rem}

/* Alert / nudge */
.nudge{display:flex;align-items:center;gap:1rem;margin-bottom:1rem;padding:1rem 1.3rem;border-radius:18px;background:var(--goldbg);border:1px solid #ead9a6;color:#6e5413;font-size:.92rem}
.nudge i{font-size:1.2rem}
.nudge span{flex:1}
.nudge .btn{margin-left:auto}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin:1.2rem 0 1.6rem}
.stat{background:var(--card);border:1px solid var(--line);border-radius:22px;padding:1.3rem;display:flex;flex-direction:column;gap:1rem;text-align:left;cursor:pointer;font:inherit;color:inherit;transition:transform .3s,box-shadow .3s,border-color .3s;animation:rise .7s ease both;text-decoration:none}
.stat:nth-child(1){animation-delay:.1s}.stat:nth-child(2){animation-delay:.18s}.stat:nth-child(3){animation-delay:.26s}.stat:nth-child(4){animation-delay:.34s}
.stat:hover{transform:translateY(-5px);box-shadow:var(--shadow);border-color:var(--sage)}
.stat.on{border-color:var(--deep);box-shadow:0 0 0 3px rgba(47,84,68,.12)}
.stat .ic{width:46px;height:46px;border-radius:14px;display:grid;place-items:center;font-size:1.05rem}
.stat b{display:block;font:600 2.2rem/1 var(--serif)}
.stat span{font-size:.84rem;color:var(--ink2)}
.ic-g{background:var(--sagebg);color:var(--deep)}.ic-y{background:var(--goldbg);color:#82641a}.ic-b{background:var(--skybg);color:#3f7084}.ic-c{background:var(--claybg);color:#a46a45}

/* Panel */
.panel{background:var(--card);border:1px solid var(--line);border-radius:28px;padding:clamp(1.1rem,2.5vw,1.8rem);animation:rise .7s .35s ease both}
.p-head{display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;margin-bottom:1.3rem}
.p-head h2{font:600 1.55rem var(--serif);letter-spacing:-.01em}
.p-head p{font-size:.9rem;color:var(--ink2);margin-top:.2rem}
.tools{display:flex;gap:.7rem;flex-wrap:wrap;align-items:center;margin-bottom:1.2rem}
.search{position:relative;flex:1;min-width:200px;max-width:340px}
.search i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--mute);font-size:.85rem}
.search input{width:100%;padding:.7rem 1rem .7rem 2.5rem;border-radius:999px;border:1.5px solid var(--line);background:#fff;font:500 .9rem var(--sans);color:var(--ink);transition:border-color .25s,box-shadow .25s}
.search input:focus{outline:0;border-color:var(--sage);box-shadow:0 0 0 4px rgba(92,138,115,.16)}
.chips{display:flex;gap:.4rem;flex-wrap:wrap}
.chip{border:1.5px solid var(--line);background:transparent;padding:.5rem .95rem;border-radius:999px;font-size:.82rem;font-weight:600;color:var(--ink2);cursor:pointer;transition:all .25s}
.chip:hover{border-color:var(--sage);color:var(--ink)}
.chip.on{background:var(--deep);border-color:var(--deep);color:#fff}

/* Table */
.tbl-wrap{overflow-x:auto;margin:0 -.4rem;padding:0 .4rem}
table{width:100%;border-collapse:separate;border-spacing:0 .5rem}
th{text-align:left;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--mute);font-weight:700;padding:.4rem 1rem}
td{padding:1rem;background:#fff;border-top:1px solid var(--line);border-bottom:1px solid var(--line);font-size:.9rem;vertical-align:middle}
td:first-child{border-left:1px solid var(--line);border-radius:16px 0 0 16px}
td:last-child{border-right:1px solid var(--line);border-radius:0 16px 16px 0}
tbody tr{animation:rise .5s ease both;transition:transform .25s}
tbody tr:hover{transform:translateY(-2px)}
tbody tr:hover td{border-color:var(--sage)}
.tid{font-weight:700;color:var(--deep)}
.t-title{font-weight:600}
.t-sub{font-size:.78rem;color:var(--mute);display:flex;align-items:center;gap:.4rem;margin-top:.15rem}
.badge{display:inline-flex;align-items:center;gap:.45rem;padding:.28rem .75rem;border-radius:999px;font-size:.74rem;font-weight:700;background:var(--bg2);color:var(--ink2);white-space:nowrap}
.badge-dot{width:7px;height:7px;border-radius:50%;background:currentColor}
.badge-pending{background:var(--goldbg);color:#82641a}
.badge-assigned,.badge-in_progress,.badge-in-progress{background:var(--skybg);color:#3f7084}
.badge-resolved,.badge-closed{background:var(--sagebg);color:var(--deep)}
.badge-rejected,.badge-cancelled{background:var(--dangerbg);color:var(--danger)}
.badge-low{background:var(--sagebg);color:var(--deep)}
.badge-medium,.badge-normal{background:var(--goldbg);color:#82641a}
.badge-high{background:var(--claybg);color:#a46a45}
.badge-urgent,.badge-critical,.badge-emergency{background:var(--dangerbg);color:var(--danger)}
.cat{background:var(--bg2);color:var(--ink2)}
.act{display:inline-flex;gap:.4rem;flex-wrap:wrap;justify-content:flex-end}
.t-right{text-align:right}
.no-match{display:none;text-align:center;padding:2.5rem 1rem;color:var(--ink2)}
.no-match.show{display:block}
.no-match i{font-size:1.6rem;color:var(--mute);margin-bottom:.6rem;display:block}

/* Pagination */
.pager{display:flex;justify-content:center;align-items:center;gap:.8rem;margin-top:1.4rem;font-size:.86rem;color:var(--ink2)}

/* Empty */
.empty{text-align:center;padding:clamp(2rem,6vw,4rem) 1rem}
.empty .art{width:96px;height:96px;border-radius:30px;background:var(--sagebg);color:var(--deep);display:grid;place-items:center;font-size:2.2rem;margin:0 auto 1.4rem;animation:bob 5s ease-in-out infinite}
.empty h3{font:600 1.5rem var(--serif);margin-bottom:.5rem}
.empty p{color:var(--ink2);max-width:420px;margin:0 auto 1.6rem}

/* Page head */
.page-head{display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;margin-bottom:1.3rem}
.page-head h1{font:600 clamp(1.5rem,3vw,2rem) var(--serif);letter-spacing:-.01em}
.page-head p{color:var(--ink2);font-size:.92rem;margin-top:.2rem}

/* Card */
.card{background:var(--card);border:1px solid var(--line);border-radius:26px;padding:clamp(1.2rem,2.6vw,1.8rem);animation:rise .7s .18s ease both}
.card h3{font:600 1.25rem var(--serif);margin-bottom:.3rem;display:flex;align-items:center;gap:.6rem}
.card h3 i{color:var(--sage);font-size:1rem}
.card .sub{font-size:.86rem;color:var(--mute);margin-bottom:1.3rem}
.card .lead{font-size:.88rem;color:var(--ink2);margin-bottom:1.5rem}
.label{font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mute);font-weight:700;margin-bottom:.6rem;display:flex;align-items:center;gap:.5rem}
.desc{color:var(--ink2);font-size:.97rem;line-height:1.7;white-space:pre-line}

/* Hero (complaint detail) */
.hero{position:relative;overflow:hidden;background:linear-gradient(120deg,var(--deep),#3d6b57);color:#fff;border-radius:28px;padding:clamp(1.4rem,3vw,2.2rem);box-shadow:var(--shadow);margin-bottom:1.3rem;animation:rise .7s .05s ease both}
.hero::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.07);top:-130px;right:-60px}
.hero>*{position:relative}
.hero-top{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap}
.eyebrow{font-size:.76rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#f1dfa8;margin-bottom:.4rem}
.hero h1{font:600 clamp(1.5rem,3.4vw,2.3rem)/1.15 var(--serif);letter-spacing:-.01em;max-width:680px}
.meta{display:flex;gap:.6rem 1.6rem;flex-wrap:wrap;margin-top:1.1rem;font-size:.86rem;color:rgba(255,255,255,.8)}
.meta span{display:inline-flex;align-items:center;gap:.5rem}
.meta i{color:#f1dfa8}
.meta strong{color:#fff;font-weight:600}

/* Progress tracker */
.track{background:var(--card);border:1px solid var(--line);border-radius:24px;padding:1.4rem 1.6rem;margin-bottom:1.3rem;animation:rise .7s .12s ease both}
.track h3{font-size:.74rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mute);margin-bottom:1.1rem}
.steps{display:grid;grid-template-columns:repeat(4,1fr);position:relative}
.steps::before{content:'';position:absolute;left:12.5%;right:12.5%;top:19px;height:3px;background:var(--bg2);border-radius:3px}
.steps .fill{position:absolute;left:12.5%;top:19px;height:3px;border-radius:3px;background:var(--sage);width:0;transition:width 1.2s .3s cubic-bezier(.2,.8,.2,1)}
.st{position:relative;text-align:center;font-size:.8rem;color:var(--mute);font-weight:600}
.st .c{width:40px;height:40px;border-radius:50%;margin:0 auto .5rem;display:grid;place-items:center;background:var(--card);border:2px solid var(--line);color:var(--mute);font-size:.85rem;transition:all .4s;position:relative;z-index:1}
.st.done .c{background:var(--sage);border-color:var(--sage);color:#fff}
.st.now .c{background:var(--deep);border-color:var(--deep);color:#fff;box-shadow:0 0 0 6px rgba(47,84,68,.15);animation:pulse 2.2s infinite}
.st.done,.st.now{color:var(--ink)}
@keyframes pulse{50%{box-shadow:0 0 0 10px rgba(47,84,68,.06)}}

/* Grid layout */
.grid{display:grid;grid-template-columns:1.7fr 1fr;gap:1.3rem;align-items:start}
.col{display:grid;gap:1.3rem;min-width:0}
.stack{display:grid;gap:1.3rem}
.two{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.toolbar{display:flex;gap:.7rem;flex-wrap:wrap;align-items:center}

/* Photo */
.photo{margin-top:1.6rem}
.photo button{display:block;width:100%;max-width:520px;border:1px solid var(--line);border-radius:20px;overflow:hidden;padding:0;background:#fff;cursor:zoom-in;position:relative}
.photo img{width:100%;display:block;transition:transform .5s}
.photo button:hover img{transform:scale(1.03)}
.photo .zoom{position:absolute;right:.8rem;bottom:.8rem;background:rgba(38,49,45,.75);color:#fff;font-size:.74rem;font-weight:600;padding:.3rem .7rem;border-radius:999px}
.lightbox{position:fixed;inset:0;background:rgba(20,28,25,.88);display:none;align-items:center;justify-content:center;padding:1.2rem;z-index:100;cursor:zoom-out}
.lightbox.show{display:flex;animation:fadein .3s}
.lightbox img{max-width:100%;max-height:92vh;border-radius:16px}
@keyframes fadein{from{opacity:0}}

/* Rating */
.rating{background:var(--sagebg);border:1px solid #c8dccc;border-radius:22px;padding:1.3rem 1.5rem}
.rating .top{display:flex;justify-content:space-between;align-items:center;gap:.8rem;flex-wrap:wrap}
.rating b{color:var(--deep)}
.stars{color:var(--gold);font-size:1.05rem;display:inline-flex;gap:.15rem;align-items:center}
.stars .n{font-size:.84rem;color:var(--ink2);margin-left:.5rem;font-weight:600}
.rating blockquote{margin:.8rem 0 0;padding:.8rem 1rem;border-left:3px solid var(--sage);background:rgba(255,255,255,.55);border-radius:0 12px 12px 0;font-size:.92rem;color:var(--ink2);font-style:italic}

/* Timeline */
.tl{position:relative;display:grid;gap:1rem}
.tl::before{content:'';position:absolute;left:19px;top:14px;bottom:14px;width:2px;background:var(--line)}
.tl li{display:flex;gap:1rem;align-items:flex-start;animation:rise .6s ease both}
.tl .av{width:40px;height:40px;border-radius:50%;background:var(--sagebg);color:var(--deep);border:2px solid var(--card);display:grid;place-items:center;font:600 .95rem var(--serif);flex-shrink:0;z-index:1;box-shadow:0 0 0 2px var(--sage)}
.tl .bd{flex:1;min-width:0;background:#fff;border:1px solid var(--line);border-radius:18px;padding:1rem 1.2rem;transition:transform .3s,border-color .3s}
.tl li:hover .bd{transform:translateX(4px);border-color:var(--sage)}
.tl .hd{display:flex;justify-content:space-between;gap:.5rem 1rem;flex-wrap:wrap;align-items:center}
.tl .who{font-weight:700;font-size:.92rem;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.role{font-size:.68rem;font-weight:700;padding:.15rem .6rem;border-radius:999px;background:var(--bg2);color:var(--ink2)}
.tl time{font-size:.78rem;color:var(--mute)}
.tl p{font-size:.92rem;color:var(--ink2);margin-top:.45rem;white-space:pre-line}
.tl .chg{margin-top:.6rem}
.note{display:flex;gap:1rem;align-items:center;padding:1.2rem 1.3rem;border-radius:18px;background:var(--bg2);border:1px dashed #cfc7b2;color:var(--ink2);font-size:.9rem}
.note i{font-size:1.4rem;color:var(--mute)}

/* Side facts */
.facts{display:grid;gap:0}
.fact{display:flex;gap:.9rem;align-items:center;padding:.85rem 0;border-top:1px dashed var(--line)}
.fact:first-child{border-top:0;padding-top:0}
.fact .ic{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-size:.9rem;flex-shrink:0}
.fact small{display:block;font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--mute);font-weight:700}
.fact b{font-size:.92rem;font-weight:600;word-break:break-word}
.aside{position:sticky;top:1.5rem}
.cta-side{margin-top:1.2rem;display:grid;gap:.6rem}
.cta-side .btn{width:100%}
.help{margin-top:1.3rem;padding:1.1rem 1.2rem;border-radius:18px;background:var(--goldbg);border:1px solid #ead9a6;font-size:.86rem;color:#6e5413}
.help b{display:flex;gap:.5rem;align-items:center;margin-bottom:.2rem}

/* Forms */
.form-group{margin-bottom:1.2rem}
.form-group>label{display:block;font-size:.85rem;font-weight:600;margin-bottom:.45rem}
.req{color:var(--danger)}
.form-group input:not([type=file]):not([type=checkbox]):not([type=radio]),
.form-group select,
.form-group textarea{width:100%;padding:.9rem 1rem;border-radius:14px;border:1.5px solid var(--line);background:#fff;font:500 .96rem var(--sans);color:var(--ink);transition:border-color .25s,box-shadow .25s;-webkit-appearance:none;appearance:none}
.form-group select{padding-right:2.6rem;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%237d887f' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center}
.form-group textarea{min-height:150px;resize:vertical;line-height:1.55}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:0;border-color:var(--sage);box-shadow:0 0 0 4px rgba(92,138,115,.16)}
.form-group input::placeholder,.form-group textarea::placeholder{color:#a8afa6}
.form-group input[type=checkbox],.form-group input[type=radio]{width:18px;height:18px;accent-color:var(--deep);margin-right:.5rem;vertical-align:middle}
.form-hint{display:block;margin-top:.45rem;font-size:.8rem;color:var(--mute)}
.field-errors{display:flex;gap:.5rem;align-items:flex-start;margin-top:.45rem;font-size:.82rem;color:var(--danger)}
.has-error input:not([type=file]),.has-error select,.has-error textarea{border-color:var(--danger)}
.count{text-align:right;font-size:.76rem;color:var(--mute);margin-top:.3rem}

/* Filters */
.filters{display:flex;gap:.7rem;flex-wrap:wrap;align-items:flex-end;margin-bottom:1.2rem}
.filters .f{display:grid;gap:.3rem;min-width:140px;flex:1 1 140px;max-width:220px}
.filters .f.grow{flex:2 1 220px;max-width:340px}
.filters label{font-size:.74rem;font-weight:700;color:var(--mute)}
.filters select,.filters input[type=search]{width:100%;padding:.65rem .9rem;border-radius:12px;border:1.5px solid var(--line);background:#fff;font:500 .88rem var(--sans);color:var(--ink)}
.filters select:focus,.filters input:focus{outline:0;border-color:var(--sage);box-shadow:0 0 0 4px rgba(92,138,115,.16)}

/* Mini table */
.mini{width:100%;border-collapse:collapse;font-size:.88rem}
.mini th,.mini td{padding:.6rem .5rem;text-align:left;background:none;border:0;border-bottom:1px dashed var(--line);border-radius:0}
.mini th{font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--mute);font-weight:700}
.mini td.n,.mini th.n{text-align:right}
.mini tbody tr{animation:none}.mini tbody tr:hover{transform:none}

/* Bar */
.bar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.2rem;animation:rise .5s ease both}

/* Badges extra */
.badge-ok{background:var(--sagebg);color:var(--deep)}
.badge-off{background:var(--bg2);color:var(--mute)}
.badge-warn{background:var(--goldbg);color:#82641a}
.badge-info{background:var(--skybg);color:#3f7084}
.inline{display:inline;margin:0}

/* Detail list */
.detail-list{display:grid;gap:.2rem;font-size:.88rem;color:var(--ink2)}
.detail-list b{color:var(--ink)}
.narrow{max-width:760px}

/* Star rating input */
.rate-stars{display:flex;flex-direction:row-reverse;justify-content:flex-end;gap:.2rem;margin:.3rem 0 1.2rem}
.rate-stars input{position:absolute;opacity:0;pointer-events:none}
.rate-stars label{font-size:2.2rem;line-height:1;color:var(--line);cursor:pointer;transition:color .2s,transform .2s}
.rate-stars label:hover,.rate-stars label:hover~label,.rate-stars input:checked~label{color:var(--gold)}
.rate-stars label:hover{transform:scale(1.12)}
.rate-stars input:focus-visible+label{outline:2px solid var(--sage);border-radius:6px}

/* Responsive */
@media(max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}}
@media(max-width:960px){
  .app{grid-template-columns:1fr}
  .sidebar{position:fixed;left:0;top:0;width:min(290px,84vw);transform:translateX(-102%);transition:transform .35s cubic-bezier(.2,.8,.2,1)}
  .sidebar.open{transform:none;box-shadow:0 0 60px rgba(0,0,0,.35)}
  .topbar{display:flex}
  .tbl-wrap{margin:0;padding:0;overflow:visible}
  table,thead,tbody,tr,td{display:block}
  thead{position:absolute;left:-9999px}
  table{border-spacing:0}
  tbody tr{background:#fff;border:1px solid var(--line);border-radius:20px;padding:.4rem 1rem;margin-bottom:.9rem}
  td,td:first-child,td:last-child{border:0;border-radius:0;padding:.6rem 0;display:flex;justify-content:space-between;align-items:center;gap:1rem;border-bottom:1px dashed var(--line);text-align:right}
  td:last-child{border-bottom:0;padding-top:.8rem}
  td::before{content:attr(data-label);font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--mute);font-weight:700;text-align:left;flex-shrink:0}
  td[data-label="Issue"]>div{text-align:right}
  td.t-right{justify-content:space-between}
  .t-sub{justify-content:flex-end}
  .grid{grid-template-columns:1fr}
  .two{grid-template-columns:1fr}
}
@media(max-width:700px){.filters .f{max-width:none}}
@media(max-width:600px){
  .stats{gap:.7rem}
  .stat{padding:1rem}
  .stat b{font-size:1.8rem}
  .search{max-width:none;flex-basis:100%}
}
@media(max-width:380px){.stats{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
</style>
@stack('head')
</head>
<body>
<div class="app">

  <aside class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a>
    <div class="nav-label">Menu</div>
    <nav class="nav">
      @auth
        @if(auth()->user()->isStudent())
          <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'on' : '' }}"><i class="fa-solid fa-table-cells-large"></i> My dashboard</a>
          <a href="{{ route('student.submit') }}"><i class="fa-solid fa-circle-plus"></i> New complaint</a>
        @elseif(auth()->user()->isPorter())
          <a href="{{ route('porter.dashboard') }}" class="{{ request()->routeIs('porter.dashboard') ? 'on' : '' }}"><i class="fa-solid fa-table-cells-large"></i> Porter dashboard</a>
        @elseif(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}"><i class="fa-solid fa-table-cells-large"></i> All complaints</a>
          <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'on' : '' }}"><i class="fa-solid fa-users"></i> Users</a>
          <a href="{{ route('admin.hostels') }}" class="{{ request()->routeIs('admin.hostels*') ? 'on' : '' }}"><i class="fa-solid fa-building"></i> Hostels</a>
          <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'on' : '' }}"><i class="fa-solid fa-tags"></i> Categories</a>
        @endif
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'on' : '' }}"><i class="fa-solid fa-user"></i> My profile</a>
      @endauth
      <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home page</a>
    </nav>
    <div class="side-tip">
      @auth
        @if(auth()->user()->isPorter())
          <b><i class="fa-solid fa-building"></i> Your hostel</b>
          You see and update complaints filed for your assigned hostel.
        @elseif(auth()->user()->isAdmin())
          <b><i class="fa-solid fa-eye"></i> Full view</b>
          You can see and oversee complaints across all hostels.
        @else
          <b><i class="fa-solid fa-lightbulb"></i> Quick tip</b>
          Select your hostel from the dropdown and describe the issue clearly.
        @endif
      @else
        <b><i class="fa-solid fa-lightbulb"></i> Quick tip</b>
        Sign in to submit and track your hostel complaints.
      @endauth
    </div>
    @auth
    <div class="me">
      <div class="avatar">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}</div>
      <div class="who"><b>{{ auth()->user()->first_name ?: auth()->user()->email }}</b><small>{{ ucfirst(strtolower(auth()->user()->role)) }}</small></div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" title="Sign out" aria-label="Sign out"><i class="fa-solid fa-right-from-bracket"></i></button>
      </form>
    </div>
    @endauth
  </aside>
  <div class="scrim" id="scrim"></div>

  <div class="main">
    <div class="topbar">
      <a href="{{ route('home') }}" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a>
      <button class="burger" id="burger" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="content">
      @foreach (['success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $type => $cls)
        @if(session($type))
          <div class="flash {{ $cls }}" role="status"><i class="fa-solid fa-circle-info"></i><span>{{ session($type) }}</span></div>
        @endif
      @endforeach
      @if ($errors->any())
        <div class="flash error" role="alert"><i class="fa-solid fa-triangle-exclamation"></i><span><ul style="margin:0;padding-left:1.2rem">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></span></div>
      @endif
      @yield('content')
    </div>
  </div>
</div>

<script>
(function(){
  var sb=document.getElementById('sidebar'),sc=document.getElementById('scrim'),bg=document.getElementById('burger');
  function toggle(o){sb.classList.toggle('open',o);sc.classList.toggle('show',o)}
  bg.addEventListener('click',function(){toggle(!sb.classList.contains('open'))});
  sc.addEventListener('click',function(){toggle(false)});
  window.addEventListener('keydown',function(e){if(e.key==='Escape')toggle(false)});
  var d=document.getElementById('today');
  if(d)d.textContent=new Date().toLocaleDateString(undefined,{weekday:'long',day:'numeric',month:'long'});
  var reduce=window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.querySelectorAll('[data-count]').forEach(function(el){
    var t=parseInt(el.getAttribute('data-count'),10)||0;
    if(reduce||!t){el.textContent=t.toLocaleString();return}
    var s=performance.now(),dur=900;
    (function tick(n){var p=Math.min((n-s)/dur,1);el.textContent=Math.round(t*(1-Math.pow(1-p,3))).toLocaleString();if(p<1)requestAnimationFrame(tick)})(s);
  });
  document.querySelectorAll('.js-status').forEach(function(s){
    var t=s.textContent.trim().replace(/_/g,' ').toLowerCase();
    s.textContent=t.charAt(0).toUpperCase()+t.slice(1);
  });
})();
</script>
@stack('scripts')
</body>
</html>
