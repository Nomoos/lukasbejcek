<?php
/**
 * Šablona pro jeden příspěvek (single.php)
 * Zobrazuje detail aktuality nebo jiného standardního příspěvku
 */
get_header();
?>

<div class="container py-5">
  <?php while (have_posts()) : the_post(); ?>
    <article>
      <h1 class="mb-2"><?php the_title(); ?></h1>
      <p class="text-muted mb-4">
        <small><?php echo esc_html(get_the_date('j. n. Y')); ?></small>
      </p>

      <?php if (has_post_thumbnail()) : ?>
        <div class="mb-4">
          <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
        </div>
      <?php endif; ?>

      <div class="entry-content">
        <?php the_content(); ?>
      </div>

      <div class="mt-5">
        <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type())); ?>" class="btn btn-outline-secondary">
          &larr; Zpět
        </a>
      </div>
    </article>
  <?php endwhile; ?>
</div>

<?php get_footer(); ?>
