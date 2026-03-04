<?php
/**
 * Template part: hero-team.php
 * Modrý pruh s logem a volitelným názvem týmu.
 * Voláno přes get_template_part('template-parts/hero', 'team', $args).
 *
 * Očekávané $args:
 *   'tym_nazev' => string – zobrazovaný název týmu (prázdný = skrytý popis)
 */

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
