<?php
/**
 * Template Name: Zápasy
 * Stránka se seznamem zápasů TJ Slavoj Mýto
 * Obsahuje: filtry (tým, sezóna, stav), seznam zápasů
 */
get_header();

// Získání filtrů z GET parametrů
$filtr_tym = isset($_GET['tym']) ? sanitize_text_field(wp_unslash($_GET['tym'])) : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav = isset($_GET['stav']) ? sanitize_text_field(wp_unslash($_GET['stav'])) : 'vse';
?>

<div class="container py-5">
    <h2 class="mb-0">Zápasy</h2>
    <p class="text-muted mb-4">Přehled všech zápasů TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <form method="get" class="d-flex gap-3 mb-4">
      <select name="tym" class="form-select filter-select-team" onchange="this.form.submit()">
        <option value="">Všechny týmy</option>
        <option value="muzi-a" <?php selected($filtr_tym, 'muzi-a'); ?>>Muži A</option>
        <option value="muzi-b" <?php selected($filtr_tym, 'muzi-b'); ?>>Muži B</option>
        <option value="dorost" <?php selected($filtr_tym, 'dorost'); ?>>Dorost</option>
        <option value="starsi-zaci" <?php selected($filtr_tym, 'starsi-zaci'); ?>>Starší žáci</option>
      </select>

      <select name="sezona" class="form-select bg-light filter-select-season" onchange="this.form.submit()">
        <option value="">Všechny sezóny</option>
        <option value="2025-26" <?php selected($filtr_sezona, '2025-26'); ?>>Sezóna 2025/26</option>
        <option value="2024-25" <?php selected($filtr_sezona, '2024-25'); ?>>Sezóna 2024/25</option>
      </select>

      <select name="stav" class="form-select filter-select-status" onchange="this.form.submit()">
        <option value="vse" <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
        <option value="odehrane" <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané zápasy</option>
        <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Budoucí zápasy</option>
      </select>
    </form>
</div>

<!-- MODRÝ PRUH S LOGEM -->
<div class="fluid">
  <div class="row">
    <div class="col-5">
      <div class="blue-bar-p"></div>
    </div>
    <div class="col-1 text-center">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" height="50">
    </div>
    <div class="col-6">
      <div class="blue-bar-l"></div>
    </div>
  </div>
</div>

<?php if ($filtr_tym) : ?>
<div class="container">
  <h1 style="text-align: center;" class="mt-3">
    <?php
    $tymy_nazvy = array(
        'muzi-a'      => 'Muži A',
        'muzi-b'      => 'Muži B',
        'dorost'      => 'Dorost',
        'starsi-zaci' => 'Starší žáci',
    );
    echo esc_html(isset($tymy_nazvy[$filtr_tym]) ? $tymy_nazvy[$filtr_tym] : $filtr_tym);
    ?>
  </h1>
</div>
<?php endif; ?>

<!-- SEZNAM ZÁPASŮ -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php
      $args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 20,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'DESC',
      );

      // Taxonomy query pro filtrování
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
              $datum = esc_html(get_post_meta(get_the_ID(), 'datum_zapasu', true));
              $cas = esc_html(get_post_meta(get_the_ID(), 'cas_zapasu', true));
              $domaci = esc_html(get_post_meta(get_the_ID(), 'domaci', true));
              $hoste = esc_html(get_post_meta(get_the_ID(), 'hoste', true));
              $skore = get_post_meta(get_the_ID(), 'skore', true);
              $strelci = esc_html(get_post_meta(get_the_ID(), 'strelci', true));
              ?>
              <div class="match-card mb-3 p-3 border rounded-4">
                <div class="row">
                  <div class="col-md-3 small text-muted">
                    <?php echo $datum; ?> v <?php echo $cas; ?><br>
                    <span class="text-secondary">Střelci: <?php echo $strelci; ?></span>
                  </div>
                  <div class="col-md-9 d-flex justify-content-between align-items-center">
                    <strong><?php echo $domaci; ?></strong>
                    <span><?php echo $skore ? esc_html($skore) : 'vs'; ?></span>
                    <strong><?php echo $hoste; ?></strong>
                  </div>
                </div>
                <div class="d-flex justify-content-between small text-muted mt-2">
                  <span>Domácí</span>
                  <span>Hosté</span>
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
