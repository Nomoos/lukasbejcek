<?php
/**
 * Template part: site-header.php
 * Hlavička webu – brand + hamburger tlačítko + navigace.
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

    <!-- Hamburger tlačítko (skryté na desktopu) -->
    <button class="nav-toggle"
            type="button"
            id="nav-toggle"
            aria-controls="site-nav"
            aria-expanded="false"
            aria-label="Otevřít navigaci">
      <span class="nav-toggle__icon" aria-hidden="true"></span>
      <span class="nav-toggle__label">Menu</span>
    </button>

  </div>

  <?php get_template_part('template-parts/mobile', 'nav'); ?>
</header>
