<?php
/**
 * 404.php
 * Stránka chyby 404 – obsah nenalezen.
 */
get_header();
?>

<div class="container py-5 text-center">
  <div class="empty-state mx-auto" style="max-width:480px;">
    <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
         width="56" height="56" fill="none" stroke="currentColor"
         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true" viewBox="0 0 24 24">
      <circle cx="11" cy="11" r="8"/>
      <line x1="21" y1="21" x2="16.65" y2="16.65"/>
      <line x1="11" y1="8" x2="11" y2="12"/>
      <line x1="11" y1="16" x2="11.01" y2="16"/>
    </svg>
    <p class="empty-state__title">Stránka nenalezena (404)</p>
    <p class="empty-state__text">Omlouváme se, požadovaná stránka neexistuje nebo byla přesunuta.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary mt-3">
      ← Zpět na úvod
    </a>
  </div>
</div>

<?php get_footer(); ?>
