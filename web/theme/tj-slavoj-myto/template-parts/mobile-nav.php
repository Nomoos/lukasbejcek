<?php
/**
 * Template part: mobile-nav.php
 * Mobilní navigační panel (hidden atribut na mobilu, visible na desktopu přes CSS).
 * Voláno z template-parts/site-header.php.
 */
?>
<nav id="site-nav" class="mobile-nav" aria-label="Hlavní menu" hidden>
  <div class="container mobile-nav__inner">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'mobile-nav__list',
        'depth'          => 2,
        'fallback_cb'    => 'slavoj_fallback_mobile_menu',
    ));
    ?>
  </div>
</nav>
