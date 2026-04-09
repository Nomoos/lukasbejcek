<?php
/**
 * 404.php — STRÁNKA CHYBY "NENALEZENO"
 * ======================================
 * WordPress automaticky použije tuto šablonu, když uživatel
 * zadá URL, která neodpovídá žádnému existujícímu obsahu.
 *
 * Např. návštěvník zadá: example.com/neexistujici-stranka
 * → WordPress nenajde obsah → zobrazí 404.php
 *
 * HTTP stavový kód 404 = "Not Found" (server nenašel požadovaný zdroj).
 * WordPress nastaví tento kód automaticky, my se staráme jen o vzhled.
 */

get_header(); // Načte header.php
?>

<!-- text-center = Bootstrap třída pro centrování textu -->
<div class="container py-5 text-center">
  <!-- mx-auto = margin auto na X ose = horizontální centrování bloku -->
  <div class="empty-state mx-auto" style="max-width:480px;">

    <!-- SVG ikona lupy s vykřičníkem — vektorová grafika přímo v HTML.
         SVG = Scalable Vector Graphics, nerozpixeluje se při zvětšení.
         aria-hidden="true" = skryje ikonu před čtečkami obrazovek
         (je pouze dekorativní, text níže popis poskytne). -->
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

    <!-- Odkaz zpět na hlavní stránku -->
    <!-- home_url('/') = URL hlavní stránky, esc_url() = bezpečnostní ošetření -->
    <!-- btn btn-primary = Bootstrap tlačítko v hlavní barvě tématu -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary mt-3">
      &larr; Zpět na úvod
    </a>
  </div>
</div>

<?php get_footer(); // Načte footer.php ?>
