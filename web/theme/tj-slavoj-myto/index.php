<?php
/**
 * index.php
 * Záložní šablona – zobrazuje seznam příspěvků (blog).
 * WordPress ji používá, pokud pro daný typ obsahu neexistuje specifičtější šablona.
 */
get_header();
?>

<div class="container py-5">
  <h1 class="mb-4">
    <?php
    if ( is_home() && ! is_front_page() ) {
        single_post_title();
    } else {
        esc_html_e( 'Blog', 'tj-slavoj-myto' );
    }
    ?>
  </h1>

  <div class="row g-4">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <div class="col-md-6">
          <div class="aktualita">
            <h4>
              <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                <?php the_title(); ?>
              </a>
            </h4>
            <p class="text-muted small mb-2"><?php echo esc_html( get_the_date( 'j. n. Y' ) ); ?></p>
            <?php the_excerpt(); ?>
          </div>
        </div>
      <?php endwhile; ?>

      <div class="col-12 mt-2">
        <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
      </div>
    <?php else : ?>
      <p class="text-muted">Žádné příspěvky nebyly nalezeny.</p>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>

