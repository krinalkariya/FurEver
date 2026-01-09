// public/assets/js/validation.js
(function () {
  const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  const PASS_RE  = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#^._-]).{8,64}$/;

  function setError(input, msg) {
    input.classList.add('is-invalid');
    const fb = input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback')
      ? input.nextElementSibling : null;
    if (fb) fb.textContent = msg || 'Invalid value';
  }
  function clearError(input) {
    input.classList.remove('is-invalid');
    const fb = input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback')
      ? input.nextElementSibling : null;
    if (fb) fb.textContent = '';
  }

  function pageContext(form) {
    const a = (form.getAttribute('action') || '').toLowerCase();
    if (a.includes('/login')) return 'login';
    if (a.includes('/register/user')) return 'register_user';
    if (a.includes('/register/ngo'))  return 'register_ngo';
    if (a.includes('/verify'))        return 'verify';
    return 'other';
  }

  function getRole(form){
    const sel = form.querySelector('select[name="role"]');
    return sel ? sel.value : 'user';
  }

  // numeric-only enforcement for phone + OTP
  function enforceDigits(el, maxLen) {
    const sanitize = () => {
      el.value = el.value.replace(/\D+/g, '').slice(0, maxLen || 99);
    };
    const keyguard = (e) => {
      const allowed = [
        'Backspace','Delete','ArrowLeft','ArrowRight','Home','End','Tab','Enter'
      ];
      if (allowed.includes(e.key)) return;
      if (!/\d/.test(e.key)) e.preventDefault();
    };
    el.addEventListener('keydown', keyguard);
    el.addEventListener('input', sanitize);
    el.addEventListener('paste', (e)=>{ e.preventDefault(); const t=(e.clipboardData||window.clipboardData).getData('text'); el.value=(el.value+t).replace(/\D+/g,'').slice(0,maxLen||99); el.dispatchEvent(new Event('input')); });
  }

  function validateField(input, ctx, role, force = false) {
    const touched = force || input.dataset.touched === '1';
    if (!touched) return true;

    const name = input.name;
    const val  = (input.value || '').trim();

    // required
    if (input.hasAttribute('required') && !val) {
      setError(input, 'This field is required.');
      return false;
    }

    // per-field rules
    if (name === 'email') {
      if (ctx === 'login' && role === 'admin') {
        // admin login: username allowed → required only (already checked)
      } else {
        if (val && !EMAIL_RE.test(val)) { setError(input, 'Enter a valid email.'); return false; }
      }
    }

    if (name === 'password') {
      if (ctx === 'register_user' || ctx === 'register_ngo') {
        if (val && !PASS_RE.test(val)) { setError(input, 'Min 8 chars with a letter, number, and symbol.'); return false; }
      } else if (ctx === 'login') {
        // only required; no strength check
        if (val.length < 1) { setError(input, 'This field is required.'); return false; }
      }
    }

    if (name === 'phone') {
      if ((ctx === 'register_user' || ctx === 'register_ngo') && !/^\d{10}$/.test(val)) {
        setError(input, 'Enter 10 digits.');
        return false;
      }
    }

    if (name === 'code') { // OTP on verify page
      if (ctx === 'verify' && !/^\d{6}$/.test(val)) { setError(input, 'Enter 6 digits.'); return false; }
    }

    // basic text fields on register pages
    if ((ctx === 'register_user' || ctx === 'register_ngo') && (name === 'name' || name === 'city')) {
      const min = name === 'name' ? 2 : 2;
      const max = name === 'name' ? 20 : 20;
      if (val.length < min || val.length > max) { setError(input, `Enter ${min}–${max} characters.`); return false; }
    }

    clearError(input);
    return true;
  }

  // leave onRoleChange as-is; it still clears error when user actually changes role
  function onRoleChange(form, ctx){
    if (ctx !== 'login') return;
    const role = getRole(form);
    const label = document.getElementById('id-label-email');
    const emailInput = document.getElementById('id-email');
    if (!label || !emailInput) return;

    if (role === 'admin') {
      label.textContent = 'Username';
      emailInput.type = 'text';
      emailInput.placeholder = 'Enter admin username';
    } else {
      label.textContent = 'Email';
      emailInput.type = 'email';
      emailInput.placeholder = 'Enter email';
    }
    // original behavior: clear errors when user changes role (but we'll preserve on initial call)
    clearError(emailInput);
  }

  function attachForm(form) {
    const ctx  = pageContext(form);

    // enforce digits on phone and OTP if present
    const phone = form.querySelector('input[name="phone"]');
    if (phone) enforceDigits(phone, 10);

    const otp = form.querySelector('input[name="code"]');
    if (otp) enforceDigits(otp, 6);

    // live validation: only after interaction
    form.querySelectorAll('input,select,textarea').forEach((inp) => {
      inp.addEventListener('input', () => { if (!inp.dataset.touched) inp.dataset.touched='1'; validateField(inp, ctx, getRole(form), true); });
      inp.addEventListener('blur',  () => { inp.dataset.touched='1'; validateField(inp, ctx, getRole(form), true); });
    });

    // role change affects login email/username field
    const roleSel = form.querySelector('select[name="role"]');
    if (roleSel) roleSel.addEventListener('change', () => onRoleChange(form, ctx));

    // INITIAL CALL: preserve any server-side email error around onRoleChange
    if (ctx === 'login') {
      const emailInput = form.querySelector('#id-email');
      let hadInvalid = false, msg = '';
      let fb = null;

      if (emailInput) {
        fb = (emailInput.nextElementSibling && emailInput.nextElementSibling.classList.contains('invalid-feedback'))
          ? emailInput.nextElementSibling : null;
        hadInvalid = emailInput.classList.contains('is-invalid');
        if (fb) msg = fb.textContent;
      }

      onRoleChange(form, ctx); // this would normally clear errors

      // restore server-side error if it existed
      if (emailInput && hadInvalid) {
        emailInput.classList.add('is-invalid');
        if (fb) fb.textContent = msg;
      }
    } else {
      onRoleChange(form, ctx);
    }

    // submit gate
    form.addEventListener('submit', (e) => {
      let ok = true;
      form.querySelectorAll('input,select,textarea').forEach((inp) => {
        if (!inp.hasAttribute('required') && !inp.value.trim()) { clearError(inp); return; }
        if (!validateField(inp, ctx, getRole(form), true)) ok = false;
      });
      if (!ok) e.preventDefault();
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-validate="auth"]').forEach(attachForm);
  });
})();
