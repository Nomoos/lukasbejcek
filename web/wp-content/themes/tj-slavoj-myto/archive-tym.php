<?php
/**
 * Archiv týmů CPT (archive-tym.php)
 * Zobrazuje seznam týmů s filtrováním podle sezóny (toggle pills)
 */
get_header();

$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : slavoj_get_latest_sezona_slug();
$sezony       = get_terms(array('taxonomy' => 'sezona', 'hide_empty' => true, 'orderby' => 'name', 'order' => 'DESC'));
$base_url     = get_post_type_archive_link('tym');
?>

<div class="container py-4">
  <h1 class="fw-bold mb-1"><?php post_type_archive_title(); ?></h1>
  <p class="text-muted mb-4">Přehled týmů <?php bloginfo('name'); ?></p>

  <!-- FILTR SEZÓN (toggle pills) -->
  <?php if (!is_wp_error($sezony) && $sezony) : ?>
    <nav class="filter-pills mb-4" aria-label="Filtrování podle sezóny">
      <?php foreach ($sezony as $sez) :
          $is_active = ($filtr_sezona === $sez->slug);
          $href      = $is_active
              ? esc_url($base_url)
              : esc_url(add_query_arg('sezona', $sez->slug, $base_url));
          $cls       = 'filter-pill' . ($is_active ? ' filter-pill--active' : '');
      ?>
        <a href="<?php echo $href; ?>"
           class="<?php echo esc_attr($cls); ?>"
           <?php if ($is_active) echo 'aria-current="true"'; ?>>
          <?php echo esc_html($sez->name); ?>
        </a>
      <?php endforeach; ?>
    </nav>
  <?php endif; ?>

  <!-- SEZNAM TÝMŮ -->
  <div class="row g-4">
    <?php
    $args = array(
        'post_type'      => 'tym',
        'posts_per_page' => -1,
    );

    if ($filtr_sezona) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'sezona',
                'field'    => 'slug',
                'terms'    => $filtr_sezona,
            ),
        );
    }

    $tymy_query = new WP_Query($args);

    if ($tymy_query->have_posts()) :
        while ($tymy_query->have_posts()) :
            $tymy_query->the_post();
            $trener = esc_html(get_post_meta(get_the_ID(), 'hlavni_trener', true));
            $pocet  = esc_html(get_post_meta(get_the_ID(), 'pocet_hracu', true));
            $kat_terms = get_the_terms(get_the_ID(), 'kategorie-tymu');
            $kat_nazev = (!is_wp_error($kat_terms) && $kat_terms) ? $kat_terms[0]->name : '';
            ?>
            <div class="col-md-4 col-lg-3">
              <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 team-card">
                  <div class="card-body text-center p-4">
                    <?php if (has_post_thumbnail()) : ?>
                      <div class="mb-3">
                        <?php the_post_thumbnail('thumbnail', array('class' => 'rounded-circle team-thumb')); ?>
                      </div>
                    <?php else : ?>
                      <div class="committee-img-wrapper mb-3">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
                             class="committee-img" alt="TJ Slavoj Mýto">
                      </div>
                    <?php endif; ?>
                    <h5 class="card-title text-dark fw-bold mb-1"><?php the_title(); ?></h5>
                    <?php if ($kat_nazev) : ?>
                      <p class="text-muted small mb-2"><?php echo esc_html($kat_nazev); ?></p>
                    <?php endif; ?>
                    <?php if ($trener) : ?>
                      <p class="text-muted small mb-0">Trenér: <?php echo $trener; ?></p>
                    <?php endif; ?>
                    <?php if ($pocet) : ?>
                      <p class="text-muted small mb-0">Hráčů: <?php echo $pocet; ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </a>
            </div>
            <?php
        endwhile;
    else :
        echo '<div class="col-12"><p class="text-muted">Žádné týmy nebyly nalezeny.</p></div>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<?php get_footer(); ?>
