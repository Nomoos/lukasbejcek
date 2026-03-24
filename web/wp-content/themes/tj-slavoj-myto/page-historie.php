<?php
/**
 * Template Name: Historie
 * Stránka s historií klubu TJ Slavoj Mýto
 * Logo je zarovnáno vpravo v textu (float), text obtéká.
 */
get_header();
?>

<section class="section">
  <div class="container">

    <header class="page-title">
      <h1 class="page-title__h1">Historie Klubu</h1>
    </header>

    <div class="historie-obsah">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
           alt="TJ Slavoj Mýto"
           class="historie-logo">
      <?php
      if (have_posts()) :
          while (have_posts()) : the_post();
              the_content();
          endwhile;
      endif;
      ?>
    </div>

  </div>
</section>

<?php get_footer(); ?>
