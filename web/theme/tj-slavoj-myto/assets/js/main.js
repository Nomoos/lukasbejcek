/**
 * assets/js/main.js
 *
 * Absolute-minimum JavaScript – only the hamburger nav toggle.
 * Primary approach is PHP/HTML/CSS; JS is progressive enhancement only.
 *
 * Pattern (per manual §10):
 *   HTML: <nav id="site-nav" hidden>  ← hidden by default
 *   CSS:  #site-nav[hidden] { display:flex!important } at desktop breakpoint
 *   JS:   toggles nav.hidden + aria-expanded (9 lines)
 *
 * Without JS on desktop: CSS overrides [hidden], nav always visible.
 * Without JS on mobile:  nav stays hidden – acceptable trade-off for simple site.
 */
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.querySelector('.nav-toggle');
    var nav = document.getElementById('site-nav');
    if (!btn || !nav) { return; }

    btn.addEventListener('click', function () {
        var isOpen = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!isOpen));
        nav.hidden = isOpen;
    });
});
