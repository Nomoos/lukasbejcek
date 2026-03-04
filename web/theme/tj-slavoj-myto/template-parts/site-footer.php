<?php
/**
 * Template part: site-footer.php
 * Patička webu – brand, kontakt, rychlé odkazy.
 * Voláno z footer.php přes get_template_part('template-parts/site', 'footer').
 */
?>
<footer class="site-footer">
  <div class="container site-footer__inner">

    <!-- Brand -->
    <div class="site-footer__brand">
      <img class="site-footer__logo"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
           alt="TJ Slavoj Mýto – logo">
      <span class="site-footer__name"><?php bloginfo('name'); ?></span>
    </div>

    <!-- Kontakt -->
    <div class="site-footer__info">
      <strong><?php bloginfo('name'); ?></strong><br>
      <?php echo esc_html(get_theme_mod('tjsm_adresa', 'Mýto 27, 338 05 Mýto')); ?><br>
      <?php
      $footer_email = sanitize_email(get_theme_mod('tjsm_email', 'tjslavojmyto@seznam.cz'));
      if ($footer_email) :
      ?>
        <a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a>
      <?php endif; ?>
    </div>

    <!-- Patičkové menu -->
    <nav aria-label="Patičková navigace">
      <?php
      wp_nav_menu(array(
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => 'footer-nav__list',
          'depth'          => 1,
          'fallback_cb'    => 'slavoj_fallback_footer_menu',
      ));
      ?>
    </nav>

  </div><!-- /.site-footer__inner -->

  <div class="container site-footer__bottom">
    <p class="site-footer__copy">
      &copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?> – Všechna práva vyhrazena.
    </p>
  </div>
</footer>
