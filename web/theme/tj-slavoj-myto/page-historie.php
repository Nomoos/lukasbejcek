<?php
/**
 * Template Name: Historie
 * Stránka s historií klubu TJ Slavoj Mýto
 * Obsahuje: text historie klubu (editovatelný přes WP admin), logo na pravé straně
 */
get_header();
?>

<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h2>
    <div class="row align-items-start">
      <div class="col-lg-7">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif;
        ?>
      </div>

      <div class="col-lg-5 text-center">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" class="img-fluid club-logo">
      </div>
    </div>
</div>

<?php get_footer(); ?>
