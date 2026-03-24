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

  <!-- KARTY NEJBLIŽŠÍCH ZÁPASŮ – seřazeny: Muži A, Muži B, Dorost, Žáci -->
  <section class="section" aria-label="Nejbližší zápasy">
    <div class="container">
      <div class="zapasy-container">
        <?php
        // Na homepage zobrazíme jen 4 hlavní kategorie
        $kategorie_poradi = array(
            'muzi-a'      => 'Muži A',
            'muzi-b'      => 'Muži B',
            'dorost'      => 'Dorost',
            'starsi-zaci' => 'Starší žáci',
        );

        foreach ( $kategorie_poradi as $slug => $nazev ) :
            $q = new WP_Query( array(
                'post_type'      => 'zapas',
                'posts_per_page' => 1,
                'meta_key'       => 'datum_zapasu',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',
                'tax_query'      => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'stav-zapasu',
                        'field'    => 'slug',
                        'terms'    => 'nadchazejici',
                    ),
                    array(
                        'taxonomy' => 'kategorie-tymu',
                        'field'    => 'slug',
                        'terms'    => $slug,
                    ),
                ),
            ) );

            if ( $q->have_posts() ) :
                $q->the_post();
                $datum  = get_post_meta( get_the_ID(), 'datum_zapasu', true );
                $cas    = get_post_meta( get_the_ID(), 'cas_zapasu', true );
                $domaci = get_post_meta( get_the_ID(), 'domaci', true );
                $hoste  = get_post_meta( get_the_ID(), 'hoste', true );
                $datum_fmt = '';
                if ( $datum ) {
                    $ts = strtotime( $datum );
                    if ( $ts ) $datum_fmt = date_i18n( 'j. n. Y', $ts );
                }
                ?>
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $nazev ); ?></h3>
                  <p><?php echo $datum_fmt ? esc_html( $datum_fmt ) : esc_html( $datum ); ?><?php echo $cas ? ' – ' . esc_html( $cas ) : ''; ?></p>
                  <p><strong><?php echo esc_html( $domaci ); ?></strong><br>vs<br><strong><?php echo esc_html( $hoste ); ?></strong></p>
                </div>
                <?php
            else :
                // Žádný nadcházející zápas pro tuto kategorii
                ?>
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $nazev ); ?></h3>
                  <p class="text-muted" style="font-size:13px">Žádný nadcházející zápas</p>
                </div>
                <?php
            endif;
            wp_reset_postdata();

        endforeach;
        ?>
      </div>
    </div>
  </section>

  <!-- DEKORATIVNÍ PRUHY -->
  <div class="fluid">
    <div class="row g-0">
      <div class="col-9">
        <div class="blue-bar-p"></div>
      </div>
    </div>
  </div>

  <div class="fluid">
    <div class="row g-0">
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
          'meta_query'     => array(
              'relation' => 'OR',
              // Příspěvky s nastaveným datem expirace >= dnes
              array(
                  'key'     => '_expiration_date',
                  'value'   => date('Y-m-d'),
                  'compare' => '>=',
                  'type'    => 'DATE',
              ),
              // Starší příspěvky bez meta (před zavedením expirace)
              array(
                  'key'     => '_expiration_date',
                  'compare' => 'NOT EXISTS',
              ),
          ),
      );
      $aktuality_query = new WP_Query( $aktuality_args );

      if ( $aktuality_query->have_posts() ) :
          echo '<div class="row g-4">';
          while ( $aktuality_query->have_posts() ) :
              $aktuality_query->the_post();
              ?>
              <div class="col-12">
                <div class="aktualita">
                  <h4><a href="<?php the_permalink(); ?>" class="text-decoration-none">
                    <?php the_title(); ?>
                  </a></h4>
                  <p class="aktualita__datum text-muted small mb-2"><?php the_date('j. n. Y'); ?></p>
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
