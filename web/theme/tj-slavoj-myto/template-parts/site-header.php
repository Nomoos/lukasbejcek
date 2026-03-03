<?php
/**
 * Template part: site-header.php
 * Hlavička webu – brand + hlavní nav + hamburger tlačítko.
 *
 * Desktop: brand vlevo, nav vpravo (flex row).
 * Mobil:   nav skrytá (hidden attr), tlačítko ji zobrazí přes JS.
 *          Bez JS: nav zůstane skrytá na mobilu; na desktopu CSS
 *          přebíjí [hidden] a nav je vždy viditelná.
 *
 * Voláno z header.php přes get_template_part('template-parts/site', 'header').
 */
?>
<header class="site-header" id="site-header">
  <div class="container site-header__inner">

    <!-- Brand / Logo -->
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <img class="brand__logo"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
           alt="">
      <span class="brand__name"><?php bloginfo('name'); ?></span>
    </a>

    <!-- Hlavní navigace (hidden na mobilu; desktop CSS přebíjí [hidden]) -->
    <nav id="site-nav" class="site-nav" aria-label="Hlavní menu" hidden>
      <?php
      wp_nav_menu(array(
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'site-nav__list',
          'depth'          => 2,
          'fallback_cb'    => 'slavoj_fallback_primary_menu',
      ));
      ?>
    </nav>

    <!-- Hamburger tlačítko (min. 44×44 px touch target) -->
    <button class="nav-toggle"
            type="button"
            aria-controls="site-nav"
            aria-expanded="false"
            aria-label="Otevřít navigaci">
      <span class="nav-toggle__icon" aria-hidden="true"></span>
      <span class="nav-toggle__label">Menu</span>
    </button>

  </div>
</header>
