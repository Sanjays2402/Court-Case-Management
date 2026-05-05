// Theme toggle + small UX niceties.
(function () {
  const root = document.documentElement;
  const KEY = 'ccms-theme';
  const stored = localStorage.getItem(KEY);
  if (stored) root.setAttribute('data-bs-theme', stored);

  const btn = document.getElementById('theme-toggle');
  if (btn) {
    const sync = () => {
      const dark = root.getAttribute('data-bs-theme') === 'dark';
      btn.innerHTML = dark
        ? '<i class="bi bi-sun"></i>'
        : '<i class="bi bi-moon-stars"></i>';
    };
    sync();
    btn.addEventListener('click', () => {
      const dark = root.getAttribute('data-bs-theme') === 'dark';
      root.setAttribute('data-bs-theme', dark ? 'light' : 'dark');
      localStorage.setItem(KEY, dark ? 'light' : 'dark');
      sync();
    });
  }

  // Confirm-on-click (data-confirm).
  document.querySelectorAll('[data-confirm]').forEach((el) => {
    el.addEventListener('click', (e) => {
      const msg = el.getAttribute('data-confirm') || 'Are you sure?';
      if (!window.confirm(msg)) e.preventDefault();
    });
  });

  // Auto-dismiss flash alerts after 5s.
  document.querySelectorAll('.alert.alert-dismissible').forEach((el) => {
    setTimeout(() => {
      try {
        const a = bootstrap.Alert.getOrCreateInstance(el);
        a.close();
      } catch (e) { /* ignore */ }
    }, 5000);
  });
})();
