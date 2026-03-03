<!-- PATIČKA -->
<footer class="site-footer">
  <div class="container">
    <div class="site-footer__inner">

      <!-- Brand -->
      <div class="site-footer__brand">
        <img class="site-footer__logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
             alt="TJ Slavoj Mýto – logo">
        <span class="site-footer__name">TJ Slavoj Mýto</span>
      </div>

      <!-- Kontakt -->
      <div class="site-footer__info">
        <strong>TJ Slavoj Mýto z.s.</strong><br>
        Mýto 27, 338 05 Mýto<br>
        <a href="mailto:tjslavojmyto@seznam.cz">tjslavojmyto@seznam.cz</a>
      </div>

      <!-- Rychlé odkazy -->
      <nav aria-label="Patičková navigace">
        <ul class="site-footer__links">
          <li><a class="site-footer__link" href="<?php echo esc_url(home_url('/zapasy/')); ?>">Zápasy</a></li>
          <li><a class="site-footer__link" href="<?php echo esc_url(home_url('/tymy/')); ?>">Týmy</a></li>
          <li><a class="site-footer__link" href="<?php echo esc_url(home_url('/galerie/')); ?>">Galerie</a></li>
          <li><a class="site-footer__link" href="<?php echo esc_url(home_url('/kontakty/')); ?>">Kontakty</a></li>
          <li><a class="site-footer__link" href="<?php echo esc_url(home_url('/sponzori/')); ?>">Sponzoři</a></li>
        </ul>
      </nav>

    </div><!-- /.site-footer__inner -->

    <div class="site-footer__bottom">
      &copy; <?php echo esc_html(gmdate('Y')); ?> TJ Slavoj Mýto z.s. – Všechna práva vyhrazena.
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
