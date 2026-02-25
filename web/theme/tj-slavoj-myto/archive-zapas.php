<?php
/**
 * Archiv zápasů CPT (archive-zapas.php)
 * Zobrazuje seznam zápasů s filtrováním podle kategorie týmu, sezóny a stavu
 */
get_header();

$filtr_tym    = isset($_GET['tym']) ? sanitize_text_field(wp_unslash($_GET['tym'])) : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav   = isset($_GET['stav']) ? sanitize_text_field(wp_unslash($_GET['stav'])) : 'vse';

// Načtení dostupných sezón a kategorií z taxonomií
$sezony      = get_terms(array('taxonomy' => 'sezona', 'hide_empty' => false));
$kategorie   = get_terms(array('taxonomy' => 'kategorie-tymu', 'hide_empty' => false));
?>

<div class="container py-5">
  <h2 class="mb-0">Zápasy</h2>
  <p class="text-muted mb-4">Přehled všech zápasů TJ Slavoj Mýto</p>

  <!-- FILTRY -->
  <form method="get" class="d-flex gap-3 mb-4 flex-wrap">
    <select name="tym" class="form-select filter-select-team" onchange="this.form.submit()">
      <option value="">Všechny týmy</option>
      <?php if (!is_wp_error($kategorie)) : ?>
        <?php foreach ($kategorie as $kat) : ?>
          <option value="<?php echo esc_attr($kat->slug); ?>" <?php selected($filtr_tym, $kat->slug); ?>>
            <?php echo esc_html($kat->name); ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>

    <select name="sezona" class="form-select bg-light filter-select-season" onchange="this.form.submit()">
      <option value="">Všechny sezóny</option>
      <?php if (!is_wp_error($sezony)) : ?>
        <?php foreach ($sezony as $sez) : ?>
          <option value="<?php echo esc_attr($sez->slug); ?>" <?php selected($filtr_sezona, $sez->slug); ?>>
            <?php echo esc_html($sez->name); ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>

    <select name="stav" class="form-select filter-select-status" onchange="this.form.submit()">
      <option value="vse" <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
      <option value="odehrane" <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané</option>
      <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Nadcházející</option>
    </select>
  </form>
</div>

<!-- MODRÝ PRUH S LOGEM -->
<div class="fluid">
  <div class="row">
    <div class="col-5"><div class="blue-bar-p"></div></div>
    <div class="col-1 text-center">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" height="50">
    </div>
    <div class="col-6"><div class="blue-bar-l"></div></div>
  </div>
</div>

<!-- SEZNAM ZÁPASŮ -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php
      $args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 30,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'DESC',
      );

      $tax_query  = array();
      $meta_query = array();

      if ($filtr_tym) {
          $tax_query[] = array(
              'taxonomy' => 'kategorie-tymu',
              'field'    => 'slug',
              'terms'    => $filtr_tym,
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

      if ($filtr_stav === 'odehrane') {
          $meta_query[] = array(
              'key'     => 'datum_zapasu',
              'value'   => current_time('Y-m-d'),
              'compare' => '<',
              'type'    => 'DATE',
          );
      } elseif ($filtr_stav === 'neodehrane') {
          $meta_query[] = array(
              'key'     => 'datum_zapasu',
              'value'   => current_time('Y-m-d'),
              'compare' => '>=',
              'type'    => 'DATE',
          );
      }

      if (!empty($meta_query)) {
          $meta_query['relation'] = 'AND';
          $args['meta_query'] = $meta_query;
      }

      $zapasy_query = new WP_Query($args);

      if ($zapasy_query->have_posts()) :
          while ($zapasy_query->have_posts()) :
              $zapasy_query->the_post();
              $datum   = esc_html(get_post_meta(get_the_ID(), 'datum_zapasu', true));
              $cas     = esc_html(get_post_meta(get_the_ID(), 'cas_zapasu', true));
              $domaci  = esc_html(get_post_meta(get_the_ID(), 'domaci', true));
              $hoste   = esc_html(get_post_meta(get_the_ID(), 'hoste', true));
              $skore   = get_post_meta(get_the_ID(), 'skore', true);
              $strelci = esc_html(get_post_meta(get_the_ID(), 'strelci', true));
              $misto   = esc_html(get_post_meta(get_the_ID(), 'misto_konani', true));
              ?>
              <div class="match-card mb-3 p-3 border rounded-4">
                <div class="row">
                  <div class="col-md-3 small text-muted">
                    <?php
                    if ($datum) {
                        $dt = DateTime::createFromFormat('Y-m-d', $datum);
                        echo $dt ? esc_html($dt->format('j. n. Y')) : $datum;
                    }
                    ?>
                    <?php echo $cas ? ' v ' . $cas : ''; ?><br>
                    <?php if ($misto) : ?>
                      <span class="text-secondary"><small><?php echo $misto; ?></small></span><br>
                    <?php endif; ?>
                    <?php if ($strelci) : ?>
                      <span class="text-secondary">Střelci: <?php echo $strelci; ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-9 d-flex justify-content-between align-items-center">
                    <strong><?php echo $domaci ? $domaci : '—'; ?></strong>
                    <span class="fs-5 fw-bold px-3">
                      <?php echo $skore ? esc_html($skore) : 'vs'; ?>
                    </span>
                    <strong><?php echo $hoste ? $hoste : '—'; ?></strong>
                  </div>
                </div>
                <div class="d-flex justify-content-between small text-muted mt-2">
                  <a href="<?php the_permalink(); ?>" class="text-decoration-none text-muted">
                    Detail zápasu &rarr;
                  </a>
                </div>
              </div>
              <?php
          endwhile;
      else :
          echo '<p class="text-center text-muted">Žádné zápasy nebyly nalezeny.</p>';
      endif;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
