<?php
/**
 * page-historie.php — STRÁNKA HISTORIE KLUBU
 * =============================================
 * Jednoduchá šablona — zobrazí obsah stránky "historie" z editoru.
 * Logo klubu je zarovnáno vpravo a text ho obtéká (CSS float).
 *
 * Toto je nejjednodušší page-*.php šablona — žádný WP_Query,
 * žádná meta pole. Pouze standardní WordPress Loop + the_content().
 */

get_header();
?>

<section class="section">
  <div class="container">

    <header class="page-title">
      <h1 class="page-title__h1">Historie Klubu</h1>
    </header>

    <div class="historie-obsah">
      <!-- Logo s CSS třídou historie-logo (float: right v CSS) →
           text z the_content() ho obtéká zleva -->
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
           alt="TJ Slavoj Mýto"
           class="historie-logo">

      <?php
      // Standardní Loop — obsah historie je psán v Gutenberg editoru
      if (have_posts()) :
          while (have_posts()) : the_post();
              the_content(); // Celý obsah stránky z editoru
          endwhile;
      endif;
      ?>
    </div>

  </div>
</section>

<?php get_footer(); ?>
