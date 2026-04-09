<?php
/**
 * Template part: site-footer.php
 * ================================
 * Patička webu — logo, kontaktní údaje, patičkové menu a copyright.
 *
 * Voláno z footer.php:
 *   get_template_part('template-parts/site', 'footer');
 *
 * BEZPEČNOSTNÍ FUNKCE použité zde:
 * - esc_url()      → ošetří URL (odstraní nebezpečné znaky)
 * - esc_html()     → převede < > & " na HTML entity (ochrana proti XSS)
 * - esc_attr()     → totéž, ale pro HTML atributy
 * - sanitize_email() → vyčistí e-mailovou adresu od neplatných znaků
 */
?>

<!-- Sémantický HTML5 tag <footer> — patička stránky -->
<footer class="site-footer">
  <div class="container site-footer__inner">

    <!-- ===== LOGO V PATIČCE ===== -->
    <div class="site-footer__brand">
      <!-- Stejné logo jako v hlavičce, cesta přes get_template_directory_uri() -->
      <img class="site-footer__logo"
           src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
           alt="TJ Slavoj Mýto – logo">
      <!-- bloginfo('name') = název webu z Nastavení → Obecné -->
      <span class="site-footer__name"><?php bloginfo('name'); ?></span>
    </div>

    <!-- ===== KONTAKTNÍ ÚDAJE ===== -->
    <div class="site-footer__info">
      <strong><?php bloginfo('name'); ?></strong><br>

      <?php
      /**
       * get_theme_mod('tjsm_adresa', 'Mýto 27, ...')
       *
       * get_theme_mod() = načte hodnotu uloženou v Customizeru (Vzhled → Přizpůsobit).
       * Druhý parametr = výchozí hodnota, pokud admin nic nenastavil.
       * esc_html() = bezpečný výpis textu (ochrana proti XSS).
       */
      ?>
      <?php echo esc_html(get_theme_mod('tjsm_adresa', 'Mýto 27, 338 05 Mýto')); ?><br>

      <?php
      /**
       * E-mail patičky:
       * 1. sanitize_email() — vyčistí e-mail od neplatných znaků (bezpečnost vstupu)
       * 2. get_theme_mod()  — načte hodnotu z Customizeru (nebo použije výchozí)
       * 3. Pokud e-mail existuje, vypíše odkaz mailto:
       *    - esc_attr() v href (bezpečný atribut)
       *    - esc_html() v textu odkazu (bezpečný obsah)
       */
      $footer_email = sanitize_email(get_theme_mod('tjsm_email', 'tjslavojmyto@seznam.cz'));
      if ($footer_email) :
      ?>
        <a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a>
      <?php endif; ?>
    </div>

    <!-- ===== PATIČKOVÉ MENU ===== -->
    <!-- <nav> s aria-label = navigační oblast pojmenovaná pro čtečky obrazovek -->
    <nav aria-label="Patičková navigace">
      <?php
      /**
       * wp_nav_menu() — vypíše menu přiřazené k lokaci 'footer'.
       *
       * 'depth' => 1 — pouze jedna úroveň (žádná submenu v patičce)
       * 'fallback_cb' — záložní funkce, pokud admin nepřiřadil žádné menu
       */
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

  <!-- ===== COPYRIGHT ŘÁDEK ===== -->
  <div class="container site-footer__bottom">
    <p class="site-footer__copy">
      <!-- gmdate('Y') = aktuální rok (např. 2026), &copy; = symbol © -->
      &copy; <?php echo esc_html(gmdate('Y')); ?> <?php bloginfo('name'); ?> – Všechna práva vyhrazena.
    </p>
  </div>
</footer>
