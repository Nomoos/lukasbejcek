<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?> TJ Slavoj Mýto</title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- HLAVIČKA -->
<header class="header" id="site-header">
  <div class="header__inner container">

    <!-- Brand / Logo -->
    <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
      <img class="brand__logo"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
           alt="TJ Slavoj Mýto – logo">
      <span class="brand__name">TJ Slavoj Mýto</span>
    </a>

    <!-- Hamburger (skrytý na desktopu) -->
    <button class="nav__toggle"
            id="nav-toggle"
            aria-controls="nav-panel"
            aria-expanded="false"
            aria-label="Otevřít navigaci">
      <!-- SVG hamburger ikona -->
      <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg"
           width="24" height="24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" viewBox="0 0 24 24">
        <line x1="3" y1="6"  x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
      <!-- SVG zavřít ikona -->
      <svg id="icon-close" xmlns="http://www.w3.org/2000/svg"
           width="24" height="24" fill="none"
           stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" viewBox="0 0 24 24"
           style="display:none;">
        <line x1="18" y1="6"  x2="6" y2="18"/>
        <line x1="6"  y1="6"  x2="18" y2="18"/>
      </svg>
    </button>

    <!-- Nav panel (slidedown na mobilu) -->
    <nav id="nav-panel" class="nav__panel" aria-label="Hlavní navigace">
      <?php
      wp_nav_menu(array(
          'theme_location' => 'main_menu',
          'container'      => false,
          'items_wrap'     => '<ul class="nav__list">%3$s</ul>',
          'depth'          => 2,
          'fallback_cb'    => 'slavoj_fallback_menu',
          'walker'         => new Slavoj_Nav_Walker(),
      ));
      ?>
    </nav>

  </div>
</header>
