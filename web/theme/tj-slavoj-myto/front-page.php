<?php
/**
 * front-page.php
 * Šablona úvodní stránky webu (WordPress ji používá jako statickou
 * domovskou stránku, pokud je v nastavení zvolena statická úvodní stránka).
 *
 * Obsahuje: úvodní banner, karty nejbližších zápasů, aktuální zprávy.
 */
get_header();
?>

  <!-- BANNER -->
  <section class="banner">
    <?php
    $banner_url = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
    if ( ! $banner_url ) {
        $banner_url = get_template_directory_uri() . '/img/banner.jpg';
    }
    ?>
    <img src="<?php echo esc_url( $banner_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
    <div class="banner-text">
      <h1 class="fs-2 fw-bold"><?php bloginfo( 'name' ); ?></h1>
      <h5><?php bloginfo( 'description' ); ?></h5>
    </div>
  </section>

  <!-- KARTY NEJBLIŽŠÍCH ZÁPASŮ -->
  <section class="section" aria-label="Nejbližší zápasy">
    <div class="container">
      <div class="zapasy-container">
        <?php
        $args = array(
            'post_type'      => 'zapas',
            'posts_per_page' => 4,
            'meta_key'       => 'datum_zapasu',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'stav-zapasu',
                    'field'    => 'slug',
                    'terms'    => 'nadchazejici',
                ),
            ),
        );
        $zapasy_query = new WP_Query( $args );

        if ( $zapasy_query->have_posts() ) :
            while ( $zapasy_query->have_posts() ) :
                $zapasy_query->the_post();
                $datum  = get_post_meta( get_the_ID(), 'datum_zapasu', true );
                $cas    = get_post_meta( get_the_ID(), 'cas_zapasu', true );
                $domaci = get_post_meta( get_the_ID(), 'domaci', true );
                $hoste  = get_post_meta( get_the_ID(), 'hoste', true );
                // Formátování data
                $datum_fmt = '';
                if ( $datum ) {
                    $ts = strtotime( $datum );
                    if ( $ts ) {
                        $datum_fmt = date_i18n( 'j. n. Y', $ts );
                    }
                }
                // Kategorie týmu jako nadpis karty
                $kategorie = get_the_terms( get_the_ID(), 'kategorie-tymu' );
                $tym_nazev = ( $kategorie && ! is_wp_error( $kategorie ) )
                    ? $kategorie[0]->name
                    : get_the_title();
                ?>
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $tym_nazev ); ?></h3>
                  <p><?php echo $datum_fmt ? esc_html( $datum_fmt ) : esc_html( $datum ); ?><?php echo $cas ? ' – ' . esc_html( $cas ) : ''; ?></p>
                  <p><strong><?php echo esc_html( $domaci ); ?></strong><br>vs<br><strong><?php echo esc_html( $hoste ); ?></strong></p>
                </div>
                <?php
            endwhile;
        else :
            // Záložní karty pokud nejsou naplánované zápasy
            $fallback_tymy = array( 'Muži A', 'Muži B', 'Dorost', 'Starší žáci' );
            foreach ( $fallback_tymy as $t ) :
                ?>
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $t ); ?></h3>
                  <p>Nejsou naplánované zápasy</p>
                </div>
                <?php
            endforeach;
        endif;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>

  <!-- DEKORATIVNÍ PRUHY -->
  <div class="fluid">
    <div class="row">
      <div class="col-9">
        <div class="blue-bar-p"></div>
      </div>
    </div>
  </div>

  <div class="fluid">
    <div class="row">
      <div class="col-3"></div>
      <div class="col-9">
        <div class="gray-bar"></div>
      </div>
    </div>
  </div>

  <!-- AKTUÁLNÍ ZPRÁVY -->
  <section class="aktuality section">
    <div class="container">
      <h2 class="mb-4">Aktuální zprávy</h2>

      <?php
      $aktuality_args = array(
          'category_name'  => 'aktuality',
          'posts_per_page' => 4,
      );
      $aktuality_query = new WP_Query( $aktuality_args );

      if ( $aktuality_query->have_posts() ) :
          echo '<div class="row g-4">';
          while ( $aktuality_query->have_posts() ) :
              $aktuality_query->the_post();
              ?>
              <div class="col-md-6">
                <div class="aktualita">
                  <h4><a href="<?php the_permalink(); ?>" class="text-decoration-none">
                    <?php the_title(); ?>
                  </a></h4>
                  <?php the_excerpt(); ?>
                </div>
              </div>
              <?php
          endwhile;
          echo '</div>';
      else :
          echo '<p class="text-muted">Zatím nejsou žádné aktuality.</p>';
      endif;
      wp_reset_postdata();
      ?>
    </div>
  </section>

<?php get_footer(); ?>
