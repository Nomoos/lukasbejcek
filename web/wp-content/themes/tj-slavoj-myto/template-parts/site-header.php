<?php
/**
 * Template part: site-header.php
 * ================================
 * Hlavička webu – navigační lišta (navbar) postavená na Bootstrap 5.
 *
 * PRINCIP TEMPLATE PARTS:
 * WordPress umožňuje rozdělit šablonu na menší kousky (parts).
 * Tento soubor se volá z header.php takto:
 *   get_template_part('template-parts/site', 'header');
 * WP spojí cestu: template-parts/ + site + - + header + .php
 *
 * BOOTSTRAP NAVBAR:
 * Na velkých obrazovkách (≥992px = lg) je menu vodorovné.
 * Na menších se skryje a zobrazí se hamburger tlačítko.
 * Vše řídí Bootstrap přes data-bs-* atributy — žádný vlastní JS.
 */
?>

<!-- Sémantický HTML5 tag <header> — hlavička stránky -->
<header class="site-header" id="site-header">

  <!-- Bootstrap <nav> — navbar-expand-lg = rozbalí se od breakpointu lg (992px) -->
  <!-- aria-label = popis pro čtečky obrazovek (přístupnost / accessibility) -->
  <nav class="navbar navbar-expand-lg" aria-label="Hlavní navigace">

    <!-- .container = Bootstrap kontejner s max-width, centruje obsah -->
    <div class="container site-header__inner">

      <!-- ===== LOGO A NÁZEV KLUBU ===== -->
      <!-- esc_url() = bezpečnostní funkce WP — ošetří URL proti XSS útokům -->
      <!-- home_url('/') = vrátí URL hlavní stránky webu -->
      <a class="navbar-brand brand" href="<?php echo esc_url(home_url('/')); ?>">

        <!-- get_template_directory_uri() = URL složky šablony
             (např. http://localhost/fotbal_club/wp-content/themes/tj-slavoj-myto) -->
        <img class="brand__logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
             alt="">

        <!-- bloginfo('name') = název webu nastavený v Nastavení → Obecné -->
        <span class="brand__name"><?php bloginfo('name'); ?></span>
      </a>

      <!-- ===== HAMBURGER TLAČÍTKO (mobilní menu) ===== -->
      <!--
        Viditelné pouze na mobilech (d-lg-none = display:none od lg nahoru).
        data-bs-toggle="collapse"  → říká Bootstrapu: "přepni viditelnost"
        data-bs-target="#site-nav"  → cílový element, který se skryje/zobrazí
        aria-controls   → propojení s cílem pro čtečky obrazovek
        aria-expanded   → "false" = menu je zavřené (BS mění automaticky)
        aria-label      → textový popis tlačítka pro nevidomé uživatele
      -->
      <button class="navbar-toggler nav-toggle d-lg-none"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#site-nav"
              aria-controls="site-nav"
              aria-expanded="false"
              aria-label="Otevřít navigaci">
        <span class="nav-toggle__icon" aria-hidden="true"></span>
        <span class="nav-toggle__label">Menu</span>
      </button>

      <!-- ===== NAVIGAČNÍ MENU ===== -->
      <!-- .collapse .navbar-collapse = Bootstrap skryje na mobilu, zobrazí na desktopu -->
      <!-- id="site-nav" = cíl hamburger tlačítka výše -->
      <div class="collapse navbar-collapse" id="site-nav">
        <?php
        /**
         * wp_nav_menu() — WordPress funkce, která vypíše navigační menu.
         *
         * Parametry:
         * 'theme_location' => 'primary'  — použij menu přiřazené k lokaci "primary"
         *                                   (registrováno ve functions.php)
         * 'container'      => false       — NEobaluj menu do <div> (nepotřebujeme)
         * 'menu_class'     => 'navbar-nav ms-auto' — Bootstrap třídy na <ul>:
         *                     navbar-nav = styl navigace
         *                     ms-auto    = margin-start:auto = zarovnání doprava
         * 'depth'          => 2           — max. 2 úrovně menu (hlavní + submenu)
         * 'fallback_cb'    => '...'       — záložní funkce, pokud menu není
         *                                   nastaveno v administraci
         */
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
