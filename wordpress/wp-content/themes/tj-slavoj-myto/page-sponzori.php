<?php
/**
 * Template Name: Sponzoři
 * Stránka se sponzory klubu TJ Slavoj Mýto
 * Obsahuje: mřížka sponzorů se stejnou velikostí, logo, název, proklik na web
 */
get_header();
?>

<div class="container py-5 text-center">
  <h2 class="fw-bold mb-5">Sponzoři</h2>

  <div class="row g-4 justify-content-center">
    <?php
    $args = array(
        'category_name'  => 'sponzori',
        'posts_per_page' => -1,
    );
    $sponzori_query = new WP_Query($args);

    if ($sponzori_query->have_posts()) :
        while ($sponzori_query->have_posts()) :
            $sponzori_query->the_post();
            $web_sponzora = get_post_meta(get_the_ID(), 'web_sponzora', true);
            $has_link = !empty($web_sponzora);
            ?>
            <div class="col-md-4 col-lg-3">
              <?php if ($has_link) : ?>
                <a href="<?php echo esc_url($web_sponzora); ?>" target="_blank" rel="noopener noreferrer" class="sponsor-card d-block">
              <?php else : ?>
                <div class="sponsor-card">
              <?php endif; ?>

                <div class="committee-img-wrapper">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium', array(
                        'class' => 'committee-img',
                        'alt'   => get_the_title(),
                    )); ?>
                  <?php else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" class="committee-img" alt="<?php the_title(); ?>">
                  <?php endif; ?>
                </div>
                <h5 class="mb-1"><?php the_title(); ?></h5>

              <?php if ($has_link) : ?>
                </a>
              <?php else : ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
        endwhile;
    else :
        echo '<p class="text-muted">Zatím nejsou k dispozici žádní sponzoři.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<?php get_footer(); ?>
