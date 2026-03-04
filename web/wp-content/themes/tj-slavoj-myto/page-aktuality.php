<?php
/**
 * Template Name: Aktuality
 * Přehledová stránka aktualit – zobrazuje příspěvky z kategorie „aktuality".
 *
 * Přístup: PHP/HTML/CSS primary – žádný JavaScript.
 * Stránkování přes GET parametr „stranka".
 */
get_header();

$paged = isset( $_GET['stranka'] ) ? max( 1, absint( $_GET['stranka'] ) ) : 1;

$aktuality_query = new WP_Query( array(
    'post_type'      => 'post',
    'category_name'  => 'aktuality',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<!-- ═════════════════════════════════
     BANNER
     ═════════════════════════════════ -->
<section class="banner">
  <?php
  $page_id    = get_queried_object_id();
  $banner_url = get_the_post_thumbnail_url( $page_id, 'full' );
  if ( ! $banner_url ) {
      $banner_url = get_template_directory_uri() . '/img/banner.jpg';
  }
  ?>
  <img src="<?php echo esc_url( $banner_url ); ?>"
       alt="<?php echo esc_attr( get_the_title( $page_id ) ); ?>">
  <div class="banner-text">
    <h1 class="fs-2 fw-bold"><?php echo esc_html( get_the_title( $page_id ) ); ?></h1>
    <h5><?php
        $sub = get_the_excerpt( $page_id );
        echo $sub ? esc_html( wp_strip_all_tags( $sub ) ) : esc_html( sprintf( 'Nejnovější zprávy %s', get_bloginfo( 'name' ) ) );
    ?></h5>
  </div>
</section>

<!-- ═════════════════════════════════
     SEZNAM AKTUALIT
     ═════════════════════════════════ -->
<section class="section aktuality" aria-label="<?php esc_attr_e( 'Seznam aktualit', 'tj-slavoj-myto' ); ?>">
  <div class="container">
    <div class="aktuality-box">
      <?php if ( $aktuality_query->have_posts() ) : ?>

        <?php while ( $aktuality_query->have_posts() ) : $aktuality_query->the_post(); ?>
          <?php $has_thumb = has_post_thumbnail(); ?>
          <article class="aktualita<?php echo $has_thumb ? ' druhy' : ''; ?>"
                   id="post-<?php the_ID(); ?>">
            <?php if ( $has_thumb ) : ?>
              <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail( 'medium', array( 'alt' => '' ) ); ?>
              </a>
            <?php endif; ?>
            <div class="text">
              <p class="aktualita__date text-muted mb-1">
                <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                  <?php echo esc_html( get_the_date( 'j. n. Y' ) ); ?>
                </time>
              </p>
              <h4>
                <a href="<?php the_permalink(); ?>" class="aktualita__link text-decoration-none">
                  <?php the_title(); ?>
                </a>
              </h4>
              <p><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>"
                 class="btn btn--outline btn-sm btn-outline-primary"
                 aria-label="<?php echo esc_attr( sprintf( __( 'Číst více: %s', 'tj-slavoj-myto' ), get_the_title() ) ); ?>">
                Číst více
              </a>
            </div>
          </article>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

        <?php
        /* ── Stránkování ── */
        $total_pages = $aktuality_query->max_num_pages;
        if ( $total_pages > 1 ) :
            $base_url   = remove_query_arg( 'stranka' );
            $sep        = strpos( $base_url, '?' ) !== false ? '&' : '?';
            $pagination = paginate_links( array(
                'base'      => $base_url . $sep . 'stranka=%#%',
                'format'    => '',
                'current'   => $paged,
                'total'     => $total_pages,
                'mid_size'  => 2,
                'prev_text' => '&larr; ' . esc_html__( 'Předchozí', 'tj-slavoj-myto' ),
                'next_text' => esc_html__( 'Další', 'tj-slavoj-myto' ) . ' &rarr;',
            ) );
            if ( $pagination ) :
        ?>
          <nav class="pagination-nav" aria-label="<?php esc_attr_e( 'Stránkování', 'tj-slavoj-myto' ); ?>">
            <?php echo wp_kses_post( $pagination ); ?>
          </nav>
        <?php
            endif;
        endif;
        ?>

      <?php else : ?>
        <div class="empty-state" role="status">
          <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
               width="48" height="48" fill="none" stroke="currentColor"
               stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
               aria-hidden="true" viewBox="0 0 24 24">
            <path d="M4 4h16v16H4z"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="4" y1="10" x2="20" y2="10"/>
          </svg>
          <p class="empty-state__title">Žádné aktuality nenalezeny</p>
          <p class="empty-state__text">Zatím nebyly přidány žádné příspěvky.</p>
        </div>
      <?php endif; ?>
    </div><!-- /.aktuality-box -->
  </div>
</section>

<?php get_footer(); ?>
