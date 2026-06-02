/* ============================================================
   Semillas y Bosques Mejorados — main.js
   Progressive enhancement only. No frameworks.
   ============================================================ */

(function () {
  'use strict';

  /* ── Mobile nav toggle ─────────────────────────────────── */
  const toggle = document.getElementById('nav-toggle');
  const mobileNav = document.getElementById('mobile-nav');

  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      mobileNav.classList.toggle('is-open', !expanded);
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!toggle.contains(e.target) && !mobileNav.contains(e.target)) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) {
        toggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('is-open');
        toggle.focus();
      }
    });
  }

  /* ── Mark current page in nav ──────────────────────────── */
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.primary-nav a, .mobile-nav-inner a').forEach(function (link) {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.setAttribute('aria-current', 'page');
    }
  });

  /* ── Contact form validation ───────────────────────────── */
  const forms = document.querySelectorAll('.contact-form[data-validate]');

  forms.forEach(function (form) {
    const msgBox = form.querySelector('[data-form-msg]');

    // Real-time: clear error on input after first submit attempt
    let submitted = false;
    form.querySelectorAll('input, textarea').forEach(function (field) {
      field.addEventListener('input', function () {
        if (submitted) validateField(field);
      });
      field.addEventListener('blur', function () {
        if (submitted) validateField(field);
      });
    });

    form.addEventListener('submit', function (e) {
      submitted = true;
      const errors = [];

      form.querySelectorAll('[required]').forEach(function (field) {
        const err = validateField(field);
        if (err) errors.push(err);
      });

      // Phone format check
      const phone = form.querySelector('[name="telefono"]');
      if (phone && phone.value.trim()) {
        const digits = phone.value.replace(/[\s\-\(\)\+]/g, '');
        if (digits.length < 6 || !/^\d+$/.test(digits)) {
          markInvalid(phone, 'Ingrese un número de teléfono válido.');
          errors.push('Teléfono inválido');
        }
      }

      if (errors.length > 0) {
        e.preventDefault();
        const firstInvalid = form.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        if (msgBox) {
          msgBox.textContent = 'Por favor corrija los campos indicados.';
          msgBox.className = 'form-msg form-msg--error';
          msgBox.removeAttribute('hidden');
        }
      }
    });
  });

  function validateField(field) {
    const val = field.value.trim();
    let error = null;

    if (field.required && !val) {
      error = 'Este campo es requerido.';
    } else if (field.type === 'email' && val) {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
        error = 'Ingrese un email válido.';
      }
    }

    if (error) {
      markInvalid(field, error);
    } else {
      markValid(field);
    }
    return error;
  }

  function markInvalid(field, msg) {
    field.setAttribute('aria-invalid', 'true');
    let hint = field.parentElement.querySelector('.field-error');
    if (!hint) {
      hint = document.createElement('span');
      hint.className = 'field-error';
      hint.setAttribute('role', 'alert');
      field.parentElement.appendChild(hint);
    }
    hint.textContent = msg;
  }

  function markValid(field) {
    field.removeAttribute('aria-invalid');
    const hint = field.parentElement.querySelector('.field-error');
    if (hint) hint.remove();
  }

  /* ── Lazy load images ──────────────────────────────────── */
  if ('IntersectionObserver' in window) {
    const imgObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }
          imgObserver.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });

    document.querySelectorAll('img[data-src]').forEach(function (img) {
      imgObserver.observe(img);
    });
  }

})();
