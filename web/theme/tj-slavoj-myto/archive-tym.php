<?php
/**
 * Archiv týmů CPT (archive-tym.php)
 * Zobrazuje seznam týmů s filtrováním podle kategorie a sezóny
 */
get_header();

$filtr_kategorie = isset($_GET['kategorie']) ? sanitize_text_field(wp_unslash($_GET['kategorie'])) : '';
$filtr_sezona    = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';

$sezony    = get_terms(array('taxonomy' => 'sezona', 'hide_empty' => false));
$kategorie = get_terms(array('taxonomy' => 'kategorie-tymu', 'hide_empty' => false));
?>

<div class="container py-5">
  <h2 class="mb-0">Týmy</h2>
  <p class="text-muted mb-4">Přehled všech týmů TJ Slavoj Mýto</p>

  <!-- FILTRY -->
  <form method="get" class="d-flex gap-3 mb-4 flex-wrap">
    <select name="kategorie" class="form-select bg-light filter-select-team-sm" onchange="this.form.submit()">
      <option value="">Všechny kategorie</option>
      <?php if (!is_wp_error($kategorie)) : ?>
        <?php foreach ($kategorie as $kat) : ?>
          <option value="<?php echo esc_attr($kat->slug); ?>" <?php selected($filtr_kategorie, $kat->slug); ?>>
            <?php echo esc_html($kat->name); ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>

    <select name="sezona" class="form-select bg-light filter-select-season-sm" onchange="this.form.submit()">
      <option value="">Všechny sezóny</option>
      <?php if (!is_wp_error($sezony)) : ?>
        <?php foreach ($sezony as $sez) : ?>
          <option value="<?php echo esc_attr($sez->slug); ?>" <?php selected($filtr_sezona, $sez->slug); ?>>
            <?php echo esc_html($sez->name); ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </form>

  <!-- SEZNAM TÝMŮ -->
  <div class="row g-4">
    <?php
    $args = array(
        'post_type'      => 'tym',
        'posts_per_page' => -1,
    );

    $tax_query = array();

    if ($filtr_kategorie) {
        $tax_query[] = array(
            'taxonomy' => 'kategorie-tymu',
            'field'    => 'slug',
            'terms'    => $filtr_kategorie,
        );
    }

    if ($filtr_sezona) {
        $tax_query[] = array(
            'taxonomy' => 'sezona',
            'field'    => 'slug',
            'terms'    => $filtr_sezona,
        );
    }

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
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
                <div class="card h-100 shadow-sm border-0 team-card" style="transition:0.2s;">
                  <div class="card-body text-center p-4">
                    <?php if (has_post_thumbnail()) : ?>
                      <div class="mb-3">
                        <?php the_post_thumbnail('thumbnail', array(
                            'class' => 'rounded-circle',
                            'style' => 'width:80px;height:80px;object-fit:cover;',
                        )); ?>
                      </div>
                    <?php else : ?>
                      <div class="committee-img-wrapper mb-3">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
                             class="committee-img" alt="TJ Slavoj Mýto">
                      </div>
                    <?php endif; ?>
                    <h5 class="card-title text-dark fw-bold mb-1"><?php the_title(); ?></h5>
                    <?php if ($kat_nazev) : ?>
                      <p class="text-muted small mb-2"><?php echo $kat_nazev; ?></p>
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
        echo '<p class="text-muted col-12">Žádné týmy nebyly nalezeny.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<?php get_footer(); ?>
