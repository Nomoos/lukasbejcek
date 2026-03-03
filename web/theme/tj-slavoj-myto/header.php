<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?> TJ Slavoj Mýto</title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <!-- HLAVIČKA -->
  <header class="header">
    <div class="header__inner container">

      <!-- Brand / Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
        <img class="brand__logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
             alt="TJ Slavoj Mýto">
        <span class="brand__name">TJ Slavoj Mýto</span>
      </a>

      <!-- Navigace -->
      <nav aria-label="Hlavní navigace">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'main_menu',
            'container'      => false,
            'menu_class'     => 'nav__list',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'depth'          => 2,
            'fallback_cb'    => 'slavoj_fallback_menu',
            'link_before'    => '',
            'link_after'     => '',
            'before'         => '',
            'after'          => '',
            'walker'         => new Slavoj_Nav_Walker(),
        ));
        ?>
      </nav>

    </div>
  </header>
