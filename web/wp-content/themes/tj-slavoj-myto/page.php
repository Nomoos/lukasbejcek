<?php
/**
 * Obecná šablona stránky (page.php)
 * Zobrazuje obsah libovolné WordPress stránky
 */
get_header();
?>

<div class="container py-5">
  <?php while (have_posts()) : the_post(); ?>
    <h1 class="mb-4"><?php the_title(); ?></h1>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  <?php endwhile; ?>
</div>

<?php get_footer(); ?>
