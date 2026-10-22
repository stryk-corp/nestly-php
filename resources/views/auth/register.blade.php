<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create an account | Nestly</title>
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
body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;min-height:100vh;min-height:100dvh}
a{color:inherit;text-decoration:none}
h1,h2,p,ul{margin:0}
ul{padding:0;list-style:none}
button{font-family:inherit}

.shell{display:grid;grid-template-columns:1.05fr 1fr;min-height:100vh;min-height:100dvh}

/* Brand side */
.brand{background:linear-gradient(155deg,var(--deep) 0%,#3d6b57 60%,#4a7a65 100%);color:#fff;padding:clamp(2rem,5vw,4.5rem);display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
.brand::before{content:'';position:absolute;width:600px;height:600px;border-radius:50%;background:rgba(255,255,255,.05);top:-200px;right:-180px;pointer-events:none}
.brand::after{content:'';position:absolute;width:340px;height:340px;border-radius:50%;background:rgba(255,255,255,.04);bottom:-100px;left:-100px;pointer-events:none}
.brand>*{position:relative}
.logo{display:inline-flex;align-items:center;gap:.65rem;font:600 1.4rem var(--serif)}
.logo-mark{width:38px;height:38px;border-radius:12px;background:rgba(255,255,255,.14);display:grid;place-items:center;font-size:1rem}
.brand-body{max-width:440px;margin:2rem 0}
.brand-body h2{font:600 clamp(2rem,4vw,2.8rem)/1.15 var(--serif);letter-spacing:-.02em;margin-bottom:.8rem}
.brand-body h2 em{font-style:italic;color:#f1dfa8}
.brand-body p{color:rgba(255,255,255,.8);font-size:1.05rem;line-height:1.6}

/* Step roadmap on brand side */
.roadmap{margin-top:2.2rem;position:relative;display:grid;gap:1.4rem}
.roadmap::before{content:'';position:absolute;left:19px;top:16px;bottom:16px;width:2px;background:rgba(255,255,255,.15)}
.roadmap li{display:flex;gap:1rem;align-items:flex-start;position:relative}
.roadmap .n{width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.1);border:2px solid rgba(255,255,255,.2);color:#fff;display:grid;place-items:center;font:600 .95rem var(--serif);flex-shrink:0;z-index:1;transition:all .3s}
.roadmap li.on .n{background:#f1dfa8;border-color:#f1dfa8;color:var(--deep);box-shadow:0 0 0 6px rgba(241,223,168,.2)}
.roadmap li.done .n{background:var(--sage);border-color:var(--sage);color:#fff}
.roadmap b{display:block;font-size:.95rem;color:#fff}
.roadmap span{font-size:.82rem;color:rgba(255,255,255,.7)}
.brand-foot{font-size:.82rem;color:rgba(255,255,255,.6)}

/* Form side */
.side{display:flex;align-items:center;justify-content:center;padding:clamp(1.5rem,4vw,3rem);position:relative}
.side::before{content:'';position:absolute;top:-10%;right:-10%;width:420px;height:420px;background:radial-gradient(circle,#d5e5d8,transparent 65%);opacity:.8;pointer-events:none}
.box{width:100%;max-width:460px;position:relative;animation:rise .8s ease both}
@keyframes rise{from{opacity:0;transform:translateY(24px)}}
.back{display:inline-flex;align-items:center;gap:.5rem;font-size:.88rem;font-weight:500;color:var(--ink2);margin-bottom:1.4rem;transition:color .2s,transform .2s}
.back:hover{color:var(--deep);transform:translateX(-3px)}
.mobile-logo{display:none;margin-bottom:1.4rem}
.mobile-logo .logo{color:var(--ink)}
.mobile-logo .logo-mark{background:var(--deep);color:#fff}
.box h1{font:600 clamp(1.9rem,4vw,2.3rem)/1.1 var(--serif);letter-spacing:-.02em;margin-bottom:.5rem}
.sub{color:var(--ink2);margin-bottom:1.4rem;font-size:.95rem}

/* Step indicator */
.progress-wrap{margin-bottom:1.6rem}
.step-bar{height:4px;background:var(--line);border-radius:999px;overflow:hidden;position:relative;margin-bottom:.8rem}
.step-bar-fill{height:100%;background:var(--deep);border-radius:999px;width:33.33%;transition:width .4s cubic-bezier(.2,.8,.2,1)}
.step-crumbs{display:flex;justify-content:space-between;font-size:.76rem;letter-spacing:.08em;text-transform:uppercase;color:var(--mute);font-weight:700}
.step-crumbs span.active{color:var(--deep)}

.msgs{display:grid;gap:.6rem;margin-bottom:1.2rem}
.msg{display:flex;gap:.7rem;align-items:flex-start;padding:.8rem 1rem;border-radius:14px;font-size:.9rem;background:var(--sagebg);color:var(--deep)}
.msg.error,.msg.danger{background:var(--dangerbg);color:var(--danger)}
.msg.warning{background:var(--goldbg);color:#6e5413}
.msg i{margin-top:.2rem}

.form-group{margin-bottom:1.1rem}
.form-group.plain{margin-bottom:1.2rem}
.form-group label.lbl{display:block;font-size:.85rem;font-weight:600;margin-bottom:.45rem}
.input{position:relative}
.input>i.lead{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--mute);font-size:.9rem;pointer-events:none;transition:color .2s}
.input input,.form-group select{width:100%;padding:.95rem 1rem .95rem 2.7rem;border-radius:14px;border:1.5px solid var(--line);background:var(--card);font:500 .98rem var(--sans);color:var(--ink);transition:border-color .25s,box-shadow .25s,background .25s;-webkit-appearance:none;appearance:none}
.form-group select{padding-left:1rem;padding-right:2.5rem;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%237d887f' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 1rem center}
.input input::placeholder{color:#a8afa6}
.input input:focus,.form-group select:focus{outline:0;border-color:var(--sage);box-shadow:0 0 0 4px rgba(92,138,115,.16);background:#fff}
.input:focus-within>i.lead{color:var(--deep)}
.toggle{position:absolute;right:.5rem;top:50%;transform:translateY(-50%);width:38px;height:38px;border:0;background:transparent;border-radius:10px;color:var(--mute);cursor:pointer;transition:background .2s,color .2s}
.toggle:hover{background:var(--bg2);color:var(--ink)}
.input.has-toggle input{padding-right:3rem}
.two{display:grid;grid-template-columns:1fr 1fr;gap:.8rem}

.form-hint{display:block;margin-top:.45rem;font-size:.8rem;color:var(--mute);line-height:1.4}
.js-error,.field-errors{display:none;gap:.5rem;align-items:flex-start;margin-top:.45rem;font-size:.82rem;color:var(--danger)}
.has-err .js-error,.has-error .field-errors{display:flex}
.has-err input,.has-err select,.has-error input,.has-error select{border-color:var(--danger)}

/* Password strength meter */
.pw-meter{margin-top:.5rem;display:flex;align-items:center;gap:.7rem}
.pw-meter .bars{flex:1;display:flex;gap:4px;height:4px}
.pw-meter .bars i{flex:1;background:var(--line);border-radius:2px;transition:background .3s}
.pw-meter span{font-size:.76rem;color:var(--mute);min-width:90px;font-weight:600}

/* Role cards */
.role-grid{display:grid;gap:.7rem;margin-bottom:1.2rem}
.role{position:relative;display:flex;align-items:center;gap:.9rem;padding:1rem 1.1rem;border-radius:18px;border:1.5px solid var(--line);background:var(--card);cursor:pointer;transition:all .25s}
.role:hover{border-color:var(--sage);background:#fff;transform:translateY(-1px)}
.role input{position:absolute;opacity:0;pointer-events:none}
.role .ic{width:42px;height:42px;border-radius:13px;display:grid;place-items:center;font-size:1.05rem;flex-shrink:0}
.role .tx{flex:1;min-width:0}
.role .tx b{display:block;font-size:.94rem}
.role .tx .d{font-size:.78rem;color:var(--ink2);display:block}
.role .tick{width:24px;height:24px;border-radius:50%;border:2px solid var(--line);display:grid;place-items:center;font-size:.7rem;color:transparent;transition:all .2s;flex-shrink:0}
.role input:checked~.tick{background:var(--deep);border-color:var(--deep);color:#fff}
.role:has(input:checked){border-color:var(--deep);background:#fff;box-shadow:0 0 0 3px rgba(47,84,68,.12)}

/* Buttons */
.actions{display:flex;gap:.7rem;margin-top:1.6rem}
.btn{flex:1;display:inline-flex;align-items:center;justify-content:center;gap:.65rem;padding:.95rem 1.5rem;border-radius:999px;border:1.5px solid transparent;font:600 .96rem var(--sans);cursor:pointer;transition:transform .25s,background .25s,box-shadow .25s,border-color .25s}
.btn:hover{transform:translateY(-2px)}
.btn-solid{background:var(--deep);color:#fff;box-shadow:0 14px 28px -16px rgba(47,84,68,.85)}
.btn-solid:hover{background:#264537}
.btn-line{background:transparent;border-color:var(--line);color:var(--ink)}
.btn-line:hover{background:var(--card);border-color:var(--sage)}
.spin{width:16px;height:16px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:sp .7s linear infinite;display:none}
.btn.loading .spin{display:inline-block}
.btn.loading .ico{display:none}
@keyframes sp{to{transform:rotate(360deg)}}

.alt{margin-top:1.8rem;padding-top:1.3rem;border-top:1px solid var(--line);text-align:center;font-size:.9rem;color:var(--ink2)}
.alt a{font-weight:700;color:var(--deep);border-bottom:2px solid var(--sagebg);transition:border-color .2s}
.alt a:hover{border-color:var(--sage)}

@media(max-width:920px){
  .shell{grid-template-columns:1fr}
  .brand{display:none}
  .mobile-logo{display:block}
  .side{align-items:flex-start;padding-top:clamp(1.5rem,6vw,3rem)}
}
@media(max-width:480px){
  .two{grid-template-columns:1fr}
}
@media(prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition:none!important}
}
</style>
</head>
<body>
<div class="shell">

  <aside class="brand" aria-hidden="true">
    <a href="{{ route('home') }}" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a>
    <div class="brand-body">
      <h2>Join your hostel. Get things <em>fixed.</em></h2>
      <p>Create an account to submit issues, track complaint tickets, and rate resolutions.</p>
      <ul class="roadmap" id="roadmap">
        <li class="on" data-s="1"><span class="n">1</span><div><b>Account &amp; security</b><span>Email address and password</span></div></li>
        <li data-s="2"><span class="n">2</span><div><b>About you</b><span>Your name and contact phone</span></div></li>
        <li data-s="3"><span class="n">3</span><div><b>Role &amp; hostel</b><span>Student or porter</span></div></li>
      </ul>
    </div>
    <div class="brand-foot">Hostel complaint management portal</div>
  </aside>

  <main class="side">
    <div class="box">
      <div class="mobile-logo"><a href="{{ route('home') }}" class="logo"><span class="logo-mark"><i class="fa-solid fa-house-chimney-window"></i></span> Nestly</a></div>
      <a href="{{ route('home') }}" class="back"><i class="fa-solid fa-arrow-left"></i> Back to home</a>

      <div class="progress-wrap">
        <div class="step-bar"><div class="step-bar-fill" id="bar"></div></div>
        <div class="step-crumbs">
          <span class="step-indicator active" data-step="1">1. Account</span>
          <span class="step-indicator" data-step="2">2. Profile</span>
          <span class="step-indicator" data-step="3">3. Role</span>
        </div>
      </div>

      <h1>Create your account</h1>
      <p class="sub">Three quick steps to set up your hostel workspace.</p>

      @if($errors->any())
      <div class="msgs" role="alert">
        @foreach($errors->all() as $error)
          <div class="msg error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $error }}</span></div>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
        @csrf

        {{-- STEP 1: Account credentials --}}
        <div class="step-section" data-step="1">
          <div class="form-group plain">
            <label for="email" class="lbl">Email address</label>
            <div class="input">
              <i class="fa-regular fa-envelope lead"></i>
              <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@university.edu" autocomplete="email" required autofocus>
            </div>
            <div class="js-error"></div>
          </div>

          <div class="form-group plain">
            <label for="password" class="lbl">Choose a password</label>
            <div class="input has-toggle">
              <i class="fa-solid fa-lock lead"></i>
              <input type="password" id="password" name="password" placeholder="At least 8 characters" autocomplete="new-password" required>
            </div>
            <div class="pw-meter">
              <div class="bars" id="meter"><i></i><i></i><i></i><i></i></div>
              <span id="meterText"></span>
            </div>
            <div class="js-error"></div>
          </div>

          <div class="form-group plain">
            <label for="password_confirmation" class="lbl">Confirm password</label>
            <div class="input has-toggle">
              <i class="fa-solid fa-lock lead"></i>
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
            </div>
            <div class="js-error"></div>
          </div>
        </div>

        {{-- STEP 2: Name & contact --}}
        <div class="step-section" data-step="2" style="display:none;">
          <div class="two">
            <div class="form-group plain">
              <label for="first_name" class="lbl">First name</label>
              <div class="input">
                <i class="fa-regular fa-user lead"></i>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First name" autocomplete="given-name" required>
              </div>
              <div class="js-error"></div>
            </div>
            <div class="form-group plain">
              <label for="last_name" class="lbl">Last name</label>
              <div class="input">
                <i class="fa-regular fa-user lead"></i>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" autocomplete="family-name" required>
              </div>
              <div class="js-error"></div>
            </div>
          </div>

          <div class="form-group plain">
            <label for="phone" class="lbl">Phone number <small style="color:var(--mute);font-weight:400">(optional)</small></label>
            <div class="input">
              <i class="fa-solid fa-phone lead"></i>
              <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. 0803 000 0000" autocomplete="tel">
            </div>
            <small class="form-hint">Used by your hostel porter when coordinating resolution of your complaints.</small>
          </div>
        </div>

        {{-- STEP 3: Role & hostel --}}
        <div class="step-section" data-step="3" style="display:none;">
          <div class="role-grid">
            <label class="role">
              <input type="radio" name="role" value="STUDENT" {{ old('role', 'STUDENT') === 'STUDENT' ? 'checked' : '' }}>
              <span class="ic" style="background:var(--sagebg);color:var(--deep)"><i class="fa-solid fa-graduation-cap"></i></span>
              <span class="tx"><b>Hostel student</b><span class="d">I live in a hostel and submit complaints.</span></span>
              <span class="tick"><i class="fa-solid fa-check"></i></span>
            </label>
            <label class="role">
              <input type="radio" name="role" value="PORTER" {{ old('role') === 'PORTER' ? 'checked' : '' }}>
              <span class="ic" style="background:var(--goldbg);color:#82641a"><i class="fa-solid fa-user-shield"></i></span>
              <span class="tx"><b>Hostel porter</b><span class="d">I manage a hostel and review complaints.</span></span>
              <span class="tick"><i class="fa-solid fa-check"></i></span>
            </label>
          </div>

          <div id="hostel-select-group" class="form-group plain">
            <label for="hostel_id" id="hostel_label" class="lbl">Select hostel <small style="color:var(--mute);font-weight:400">(optional for porters at registration)</small></label>
            <select name="hostel_id" id="hostel_id">
              <option value="">Select a hostel</option>
              @foreach($hostels as $hostel)
                <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                  {{ $hostel->name }}
                </option>
              @endforeach
            </select>
            <small class="form-hint">Porter accounts require administrator approval before first sign-in. Students can log in immediately after registration.</small>
            <div class="js-error"></div>
          </div>
        </div>

        <div class="actions">
          <button type="button" id="backBtn" class="btn btn-line" style="display:none;"><i class="fa-solid fa-arrow-left"></i> Back</button>
          <button type="button" id="nextBtn" class="btn btn-solid">Next <i class="fa-solid fa-arrow-right"></i></button>
          <button type="submit" id="submitBtn" class="btn btn-solid" style="display:none;"><i class="fa-solid fa-check ico"></i><span class="spin"></span><span>Complete registration</span></button>
        </div>
      </form>

      <p class="alt">Already registered? <a href="{{ route('login') }}">Sign in to your account</a></p>
    </div>
  </main>
</div>

<script>
(function(){
  var totalSteps=3, currentStep=1;
  var sections=Array.from(document.querySelectorAll('.step-section'));
  var indicators=Array.from(document.querySelectorAll('.step-indicator'));
  var roadmap=Array.from(document.querySelectorAll('#roadmap li'));
  var bar=document.getElementById('bar');
  var backBtn=document.getElementById('backBtn'), nextBtn=document.getElementById('nextBtn'), submitBtn=document.getElementById('submitBtn');
  var form=document.getElementById('registerForm');
  var hostelGroup=document.getElementById('hostel-select-group'), hostelLabel=document.getElementById('hostel_label');
  var roleInputs=Array.from(document.querySelectorAll('input[name="role"]'));

  function currentRole(){var c=roleInputs.filter(function(r){return r.checked})[0];return c?c.value:'STUDENT'}

  function applyRoleDependentFields(){
    var role=currentRole();
    hostelGroup.style.display='block';
    hostelLabel.textContent=role==='PORTER'?'Hostel to manage (optional)':'Select your hostel (optional)';
  }

  function updateStepDisplay(){
    sections.forEach(function(s){s.style.display=Number(s.dataset.step)===currentStep?'block':'none'});
    indicators.forEach(function(i){
      var n=Number(i.dataset.step);
      i.classList.toggle('active',n===currentStep);
      i.classList.toggle('done',n<currentStep);
    });
    roadmap.forEach(function(li){
      var n=Number(li.dataset.s);
      li.classList.toggle('on',n===currentStep);
      li.classList.toggle('done',n<currentStep);
      if(n<currentStep){li.querySelector('.n').innerHTML='<i class="fa-solid fa-check"></i>'}
    });
    bar.style.width=(currentStep/totalSteps*100)+'%';
    backBtn.style.display=currentStep===1?'none':'inline-flex';
    nextBtn.style.display=currentStep===totalSteps?'none':'inline-flex';
    submitBtn.style.display=currentStep===totalSteps?'inline-flex':'none';
    applyRoleDependentFields();
  }

  // Inline validation for the current step
  function setError(input,msg){
    var group=input.closest('.form-group');
    var box=group.querySelector('.js-error');
    group.classList.toggle('has-err',!!msg);
    if(box){box.style.display=msg?'flex':'none';box.innerHTML=msg?'<i class="fa-solid fa-circle-exclamation"></i> '+msg:''}
  }

  function validateStep(n){
    var sec=sections[n-1], ok=true, first=null;
    function fail(el,msg){setError(el,msg);ok=false;if(!first)first=el}
    sec.querySelectorAll('input').forEach(function(el){
      if(el.type==='hidden')return;
      setError(el,'');
    });
    if(n===1){
      var email=sec.querySelector('input[type="email"]')||sec.querySelectorAll('input')[0];
      var p1=sec.querySelectorAll('input[type="password"]')[0], p2=sec.querySelectorAll('input[type="password"]')[1];
      if(!email.value.trim()||!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value.trim()))fail(email,'Enter a valid email address.');
      if(p1&&p1.value.length<8)fail(p1,'Password must be at least 8 characters.');
      if(p1&&p2&&p1.value!==p2.value)fail(p2,'The two passwords do not match.');
      else if(p2&&!p2.value)fail(p2,'Please confirm your password.');
    }
    if(n===2){
      var inputs=sec.querySelectorAll('input');
      if(!inputs[0].value.trim())fail(inputs[0],'Please enter your first name.');
      if(!inputs[1].value.trim())fail(inputs[1],'Please enter your last name.');
    }
    if(first)first.focus();
    return ok;
  }

  backBtn.addEventListener('click',function(){if(currentStep>1){currentStep--;updateStepDisplay()}});
  nextBtn.addEventListener('click',function(){
    if(currentStep<totalSteps&&validateStep(currentStep)){currentStep++;updateStepDisplay()}
  });
  form.addEventListener('keydown',function(e){
    if(e.key==='Enter'&&currentStep<totalSteps&&e.target.tagName!=='BUTTON'){e.preventDefault();nextBtn.click()}
  });
  form.addEventListener('submit',function(e){
    submitBtn.classList.add('loading');submitBtn.disabled=true;
  });
  window.addEventListener('pageshow',function(){submitBtn.classList.remove('loading');submitBtn.disabled=false});
  roleInputs.forEach(function(r){r.addEventListener('change',applyRoleDependentFields)});

  // Show/hide password toggles
  document.querySelectorAll('input[type="password"]').forEach(function(inp){
    var b=document.createElement('button');
    b.type='button';b.className='toggle';b.setAttribute('aria-label','Show password');
    b.innerHTML='<i class="fa-regular fa-eye"></i>';
    inp.parentNode.appendChild(b);
    b.addEventListener('click',function(){
      var show=inp.type==='password';
      inp.type=show?'text':'password';
      b.innerHTML=show?'<i class="fa-regular fa-eye-slash"></i>':'<i class="fa-regular fa-eye"></i>';
      b.setAttribute('aria-label',show?'Hide password':'Show password');
    });
  });

  // Password strength
  var pw=document.querySelectorAll('input[type="password"]')[0];
  var segs=document.querySelectorAll('#meter i'), txt=document.getElementById('meterText');
  var colors=['#c97a5c','#d9a24a','#8fb08f','#4f7f66'], labels=['Too weak','Could be stronger','Good','Strong'];
  if(pw){pw.addEventListener('input',function(){
    var v=pw.value, s=0;
    if(v.length>=8)s++;
    if(/[a-z]/.test(v)&&/[A-Z]/.test(v))s++;
    if(/\d/.test(v))s++;
    if(/[^A-Za-z0-9]/.test(v)||v.length>=12)s++;
    segs.forEach(function(g,i){g.style.background=(v&&i<s)?colors[s-1]:''});
    txt.textContent=v?labels[Math.max(s-1,0)]:'';
  })}

  @if($errors->has('role'))
    currentStep=3;
  @elseif($errors->has('first_name') || $errors->has('last_name'))
    currentStep=2;
  @endif

  updateStepDisplay();
})();
</script>
</body>
</html>
