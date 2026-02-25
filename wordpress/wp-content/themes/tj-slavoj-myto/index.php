<?php
/**
 * Template Name: Domů
 * Hlavní stránka TJ Slavoj Mýto
 * Obsahuje: úvodní banner, karty nejbližších zápasů, aktuální zprávy
 */
get_header();
?>

  <!-- BANNER -->
  <section class="banner">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/banner.jpg" alt="TJ Slavoj Mýto">
    <div class="banner-text">
      <h1 class="fs-2 fw-bold">TJ Slavoj Mýto</h1>
      <h2>Fotbalový klub s tradicí od roku 1909</h2>
    </div>
  </section>

  <!-- KARTY NEJBLIŽŠÍCH ZÁPASŮ -->
  <div class="container zapasy-container">
    <?php
    $args = array(
        'category_name'  => 'zapasy',
        'posts_per_page' => 4,
        'meta_key'       => 'datum_zapasu',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'datum_zapasu',
                'value'   => current_time('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );
    $zapasy_query = new WP_Query($args);

    if ($zapasy_query->have_posts()) :
        while ($zapasy_query->have_posts()) :
            $zapasy_query->the_post();
            $tym = esc_html(get_post_meta(get_the_ID(), 'tym', true));
            $datum = esc_html(get_post_meta(get_the_ID(), 'datum_zapasu', true));
            $cas = esc_html(get_post_meta(get_the_ID(), 'cas_zapasu', true));
            $domaci = esc_html(get_post_meta(get_the_ID(), 'domaci', true));
            $hoste = esc_html(get_post_meta(get_the_ID(), 'hoste', true));
            ?>
            <div class="card">
              <h3 class="h3"><?php echo $tym ? $tym : the_title('', '', false); ?></h3>
              <p><?php echo $datum; ?> – <?php echo $cas; ?></p>
              <p><strong><?php echo $domaci; ?></strong><br>vs<br><strong><?php echo $hoste; ?></strong></p>
            </div>
            <?php
        endwhile;
    else :
        // Záložní statické karty pokud nejsou k dispozici příspěvky
        ?>
        <div class="card">
          <h3 class="h3">Muži A</h3>
          <p>Nejsou naplánované zápasy</p>
        </div>
        <div class="card">
          <h3 class="h3">Muži B</h3>
          <p>Nejsou naplánované zápasy</p>
        </div>
        <div class="card">
          <h3 class="h3">Dorost</h3>
          <p>Nejsou naplánované zápasy</p>
        </div>
        <div class="card">
          <h3 class="h3">Starší žáci</h3>
          <p>Nejsou naplánované zápasy</p>
        </div>
        <?php
    endif;
    wp_reset_postdata();
    ?>
  </div>

  <!-- DEKORATIVNÍ PRUHY -->
  <div class="fluid">
    <div class="row">
      <div class="col-9">
        <div class="blue-bar-p"></div>
      </div>
    </div>
  </div>

  <br>

  <div class="fluid">
    <div class="row">
      <div class="col-3"></div>
      <div class="col-9">
        <div class="gray-bar"></div>
      </div>
    </div>
  </div>

  <!-- AKTUÁLNÍ ZPRÁVY -->
  <div class="container">
    <h2 class="mb-4">Aktuální zprávy</h2>

    <?php
    $args = array(
        'category_name'  => 'aktuality',
        'posts_per_page' => 5,
    );
    $aktuality_query = new WP_Query($args);

    if ($aktuality_query->have_posts()) :
        echo '<div class="row g-4">';
        while ($aktuality_query->have_posts()) :
            $aktuality_query->the_post();
            ?>
            <div class="col-6">
              <div class="bg-light border p-3 rounded">
                <h5 class="text-primary"><?php the_title(); ?></h5>
                <p><?php the_excerpt(); ?></p>
              </div>
            </div>
            <?php
        endwhile;
        echo '</div>';
    else :
        echo '<p>Zatím nejsou žádné aktuality.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>

<?php get_footer(); ?>
