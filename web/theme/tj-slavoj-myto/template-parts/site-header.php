<?php
/**
 * Template part: site-header.php
 * Hlavička webu – Bootstrap navbar s data-bs-toggle="collapse".
 *
 * Toggle je řešen Bootstrapem (data atributy) → žádný custom JavaScript.
 * wp_nav_menu() dostane třídy nav-item / nav-link přes filtry v functions.php.
 *
 * Voláno z header.php přes get_template_part('template-parts/site', 'header').
 */
?>
<header class="site-header" id="site-header">
  <nav class="navbar navbar-expand-lg" aria-label="Hlavní navigace">
    <div class="container site-header__inner">

      <!-- Brand / Logo -->
      <a class="navbar-brand brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img class="brand__logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
             alt="">
        <span class="brand__name"><?php bloginfo('name'); ?></span>
      </a>

      <!--
        Hamburger tlačítko (Bootstrap navbar-toggler).
        data-bs-toggle="collapse" → Bootstrap JS řídí otevírání/zavírání.
        Žádný custom JavaScript.
        min-width/min-height ≥ 44 px pro touch target (řešeno v CSS).
      -->
      <button class="navbar-toggler nav-toggle"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#site-nav"
              aria-controls="site-nav"
              aria-expanded="false"
              aria-label="Otevřít navigaci">
        <span class="nav-toggle__icon" aria-hidden="true"></span>
        <span class="nav-toggle__label">Menu</span>
      </button>

      <!-- Collapse wrapper – Bootstrap řídí zobrazení/skrytí -->
      <div class="collapse navbar-collapse" id="site-nav">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'navbar-nav ms-auto',
            'depth'          => 2,
            'fallback_cb'    => 'slavoj_fallback_primary_menu',
        ));
        ?>
      </div><!-- /.navbar-collapse -->

    </div><!-- /.site-header__inner -->
  </nav>
</header>
