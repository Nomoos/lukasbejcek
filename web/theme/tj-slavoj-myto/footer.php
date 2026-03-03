  <!-- PATIČKA -->
  <footer class="site-footer">
    <div class="container">
      <div class="site-footer__inner">

        <!-- Brand -->
        <div class="site-footer__brand">
          <img class="site-footer__logo"
               src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
               alt="TJ Slavoj Mýto">
          <span class="site-footer__name">TJ Slavoj Mýto</span>
        </div>

        <!-- Kontakt -->
        <div class="site-footer__info">
          <strong>TJ Slavoj Mýto z.s.</strong><br>
          Mýto 27, 338 05 Mýto<br>
          <a href="mailto:tjslavojmyto@seznam.cz" style="color:rgba(255,255,255,.85);">tjslavojmyto@seznam.cz</a>
        </div>

        <!-- Rychlé odkazy -->
        <nav aria-label="Patičková navigace">
          <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.35rem;">
            <li><a class="nav__link" style="color:rgba(255,255,255,.85);" href="<?php echo esc_url(home_url('/zapasy/')); ?>">Zápasy</a></li>
            <li><a class="nav__link" style="color:rgba(255,255,255,.85);" href="<?php echo esc_url(home_url('/tymy/')); ?>">Týmy</a></li>
            <li><a class="nav__link" style="color:rgba(255,255,255,.85);" href="<?php echo esc_url(home_url('/galerie/')); ?>">Galerie</a></li>
            <li><a class="nav__link" style="color:rgba(255,255,255,.85);" href="<?php echo esc_url(home_url('/kontakty/')); ?>">Kontakty</a></li>
          </ul>
        </nav>

      </div><!-- /.site-footer__inner -->

      <div class="site-footer__bottom">
        &copy; <?php echo esc_html(date('Y')); ?> TJ Slavoj Mýto z.s. – Všechna práva vyhrazena.
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
