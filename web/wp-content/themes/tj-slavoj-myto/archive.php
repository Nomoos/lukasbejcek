<?php
/**
 * Obecná archivní šablona (archive.php)
 * Zobrazuje seznam příspěvků pro kategorii, taxonomii, měsíc apod.
 */
get_header();
?>

<div class="container py-5">
  <h1 class="mb-4">
    <?php
    if (is_category()) {
        echo 'Kategorie: ' . esc_html(single_cat_title('', false));
    } elseif (is_tag()) {
        echo 'Štítek: ' . esc_html(single_tag_title('', false));
    } elseif (is_tax()) {
        echo esc_html(single_term_title('', false));
    } elseif (is_date()) {
        echo esc_html(get_the_date('F Y'));
    } else {
        echo 'Archiv';
    }
    ?>
  </h1>

  <div class="row g-4">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <div class="col-md-6">
          <div class="card h-100 shadow-sm">
            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
              </a>
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                  <?php the_title(); ?>
                </a>
              </h5>
              <p class="card-text text-muted small"><?php echo esc_html(get_the_date('j. n. Y')); ?></p>
              <p class="card-text"><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">Číst více</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else : ?>
      <p class="text-muted">Žádné příspěvky nebyly nalezeny.</p>
    <?php endif; ?>
  </div>

  <div class="mt-5">
    <?php the_posts_pagination(array('mid_size' => 2)); ?>
  </div>
</div>

<?php get_footer(); ?>
