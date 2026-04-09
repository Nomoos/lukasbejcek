<?php
/**
 * template-parts/hero-team.php — HERO PRUH S LOGEM TÝMU
 * =======================================================
 * Dekorativní komponenta — modrý pruh s logem klubu a volitelným názvem.
 *
 * Voláno přes:
 *   get_template_part('template-parts/hero', 'team', array('tym_nazev' => 'Muži A'));
 *
 * Toto je příklad JEDNODUCHÉ znovupoužitelné komponenty.
 * Přijímá jen 1 parametr (tym_nazev) a vykreslí dekorativní pruh.
 *
 * POZN: V aktuálním stavu webu se tento template part NEPOUŽÍVÁ —
 * šablony archive-zapas.php a archive-galerie.php mají podobný pruh
 * přímo v sobě (inline HTML). V budoucnu by se dal nahradit voláním
 * tohoto template partu (DRY princip = Don't Repeat Yourself).
 */

// Načtení názvu týmu z $args (výchozí = prázdný string = nezobrazí se)
$tym_nazev = isset($args['tym_nazev']) ? $args['tym_nazev'] : '';
?>
<div class="team-hero">
  <div class="team-hero__bar">
    <img class="team-hero__logo"
         src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
         alt="TJ Slavoj Mýto">
  </div>
  <?php if ($tym_nazev) : ?>
  <p class="team-hero__title container"><?php echo esc_html($tym_nazev); ?></p>
  <?php endif; ?>
</div>
