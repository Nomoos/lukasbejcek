/**
 * Hamburger navigace – toggle panel pro mobilní zařízení.
 * Enqueued via slavoj_enqueue_scripts() v functions.php.
 */
(function () {
  'use strict';

  var btn      = document.getElementById('nav-toggle');
  var panel    = document.getElementById('nav-panel');
  var iconMenu  = document.getElementById('icon-menu');
  var iconClose = document.getElementById('icon-close');

  if (!btn || !panel) { return; }

  function openPanel() {
    panel.classList.add('is-open');
    btn.setAttribute('aria-expanded', 'true');
    if (iconMenu)  { iconMenu.style.display  = 'none'; }
    if (iconClose) { iconClose.style.display = ''; }
  }

  function closePanel() {
    panel.classList.remove('is-open');
    btn.setAttribute('aria-expanded', 'false');
    if (iconMenu)  { iconMenu.style.display  = ''; }
    if (iconClose) { iconClose.style.display = 'none'; }
  }

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    panel.classList.contains('is-open') ? closePanel() : openPanel();
  });

  /* Zavřít kliknutím mimo panel */
  document.addEventListener('click', function (e) {
    if (!panel.contains(e.target) && !btn.contains(e.target)) {
      closePanel();
    }
  });

  /* Zavřít po výběru odkazu */
  panel.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', closePanel);
  });

  /* Escape key */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closePanel(); }
  });
}());
