<?php
/**
 * Archiv zápasů CPT (archive-zapas.php)
 * Zobrazuje seznam zápasů s filtrováním podle kategorie týmu, sezóny a stavu
 */
get_header();

/* Výchozí: Muži A + nejnovější sezóna */
$filtr_tym    = isset($_GET['kat'])     ? sanitize_text_field(wp_unslash($_GET['kat']))    : 'muzi-a';
$filtr_sezona = isset($_GET['sezona'])  ? sanitize_text_field(wp_unslash($_GET['sezona'])) : slavoj_get_latest_sezona_slug();
$filtr_stav   = isset($_GET['stav'])    ? sanitize_text_field(wp_unslash($_GET['stav']))   : 'vse';
$paged        = isset($_GET['stranka']) ? max(1, absint($_GET['stranka']))                 : 1;

/* Taxonomie pro filtry */
$sezony    = get_terms(array('taxonomy' => 'sezona',        'hide_empty' => true, 'orderby' => 'name',    'order' => 'DESC'));
$kategorie = array_filter(
    slavoj_sort_tymy(get_terms(array('taxonomy' => 'kategorie-tymu', 'hide_empty' => false))),
    fn($t) => $t->slug !== 'stara-garda'
);
?>

<div class="container py-4">

  <!-- Záhlaví -->
  <h1 class="fw-bold mb-1"><?php post_type_archive_title(); ?></h1>
  <p class="text-muted mb-4">Přehled zápasů <?php bloginfo('name'); ?></p>

  <!-- Filtry: 3 selecty na jednom řádku na desktopu -->
  <form method="get" action="<?php echo esc_url(get_post_type_archive_link('zapas')); ?>" aria-label="Filtrování zápasů">
    <div class="row g-2 mb-4">

      <div class="col-12 col-md-4">
        <label class="visually-hidden" for="f-kat">Tým</label>
        <select id="f-kat" name="kat" class="form-select filter-select-team" onchange="this.form.submit()">
          <?php if (!is_wp_error($kategorie)) : foreach ($kategorie as $kat) : ?>
            <option value="<?php echo esc_attr($kat->slug); ?>" <?php selected($filtr_tym, $kat->slug); ?>>
              <?php echo esc_html($kat->name); ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="col-12 col-md-4">
        <label class="visually-hidden" for="f-sezona">Sezóna</label>
        <select id="f-sezona" name="sezona" class="form-select filter-select-season" onchange="this.form.submit()">
          <?php if (!is_wp_error($sezony)) : foreach ($sezony as $sez) : ?>
            <option value="<?php echo esc_attr($sez->slug); ?>" <?php selected($filtr_sezona, $sez->slug); ?>>
              Sezóna <?php echo esc_html($sez->name); ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="col-12 col-md-4">
        <label class="visually-hidden" for="f-stav">Stav</label>
        <select id="f-stav" name="stav" class="form-select filter-select-status" onchange="this.form.submit()">
          <option value="vse"        <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
          <option value="odehrane"   <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané</option>
          <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Nadcházející</option>
        </select>
      </div>

    </div>
  </form>

</div>

<!-- MODRÝ PRUH S LOGEM -->
<div class="fluid">
  <div class="row align-items-center g-0">
    <div class="col-5"><div class="blue-bar-p"></div></div>
    <div class="col-2 d-flex justify-content-center">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png" alt="TJ Slavoj Mýto" height="50">
    </div>
    <div class="col-5"><div class="blue-bar-l"></div></div>
  </div>
</div>

<!-- NÁZEV VYBRANÉHO TÝMU -->
<?php
$active_team = null;
if ($filtr_tym && !is_wp_error($kategorie)) {
    foreach ($kategorie as $kat) {
        if ($kat->slug === $filtr_tym) { $active_team = $kat->name; break; }
    }
}
if ($active_team) : ?>
  <h2 class="text-center fw-bold my-3"><?php echo esc_html($active_team); ?></h2>
<?php endif; ?>

<!-- SEZNAM ZÁPASŮ -->
<div class="container">
  <div class="matches__list">
      <?php
      $args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 10,
          'paged'          => $paged,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
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
              $id      = get_the_ID();
              $datum   = get_post_meta($id, 'datum_zapasu', true);
              $cas     = get_post_meta($id, 'cas_zapasu',   true);
              $domaci  = get_post_meta($id, 'domaci',       true);
              $hoste   = get_post_meta($id, 'hoste',        true);
              $skore   = get_post_meta($id, 'skore',        true);
              $strelci = get_post_meta($id, 'strelci',      true);

              $je_odehrany   = !empty($skore);
              $vysledek      = $je_odehrany ? slavoj_zapas_vysledek($domaci, $hoste, $skore) : '';
              $slavoj_domaci = slavoj_is_club_team($domaci);

              $datum_fmt = '';
              if ($datum) {
                  $ts = strtotime($datum);
                  if ($ts) $datum_fmt = date_i18n('j. n. Y', $ts);
              }

              $card_cls = $je_odehrany ? 'match-card--played' : 'match-card--upcoming';
              if ($vysledek === 'vyhral')  $card_cls .= ' match-card--win';
              if ($vysledek === 'prohral') $card_cls .= ' match-card--loss';
              if ($vysledek === 'remiza')  $card_cls .= ' match-card--draw';

              $score_cls = 'match-card__score';
              if ($je_odehrany) {
                  if ($vysledek === 'vyhral')      $score_cls .= ' match-card__score--win';
                  elseif ($vysledek === 'prohral') $score_cls .= ' match-card__score--loss';
                  else                             $score_cls .= ' match-card__score--draw';
              } else {
                  $score_cls .= ' match-card__score--upcoming';
              }

              $badge_cls  = $je_odehrany ? 'badge--neutral' : 'badge--primary';
              $badge_text = $je_odehrany ? 'Odehráno'       : 'Nadcházející';

              $home_cls = 'match-card__team match-card__team--home'
                          . ($slavoj_domaci ? ' match-card__team--slavoj' : '');
              $away_cls = 'match-card__team match-card__team--away'
                          . (!$slavoj_domaci && slavoj_is_club_team($hoste) ? ' match-card__team--slavoj' : '');

              get_template_part('template-parts/card', 'match', array(
                  'datum'      => $datum,
                  'datum_fmt'  => $datum_fmt,
                  'cas'        => $cas,
                  'domaci'     => $domaci,
                  'hoste'      => $hoste,
                  'skore'      => $skore,
                  'strelci'    => $strelci,
                  'card_cls'   => $card_cls,
                  'score_cls'  => $score_cls,
                  'badge_cls'  => $badge_cls,
                  'badge_text' => $badge_text,
                  'home_cls'   => $home_cls,
                  'away_cls'   => $away_cls,
              ));
          endwhile;
      else :
          echo '<p class="text-center text-muted">Žádné zápasy nebyly nalezeny.</p>';
      endif;

      /* ── Stránkování ── */
      $total_pages = $zapasy_query->max_num_pages;
      if ($total_pages > 1) {
          $base_url   = remove_query_arg('stranka');
          $sep        = strpos($base_url, '?') !== false ? '&' : '?';
          $pagination = paginate_links(array(
              'base'      => $base_url . $sep . 'stranka=%#%',
              'format'    => '',
              'current'   => $paged,
              'total'     => $total_pages,
              'mid_size'  => 2,
              'prev_text' => '&larr; ' . esc_html__('Předchozí', 'tj-slavoj-myto'),
              'next_text' => esc_html__('Další', 'tj-slavoj-myto') . ' &rarr;',
          ));
          if ($pagination) {
              echo '<nav class="pagination-nav mt-4" aria-label="' . esc_attr__('Stránkování', 'tj-slavoj-myto') . '">' . wp_kses_post($pagination) . '</nav>';
          }
      }

      wp_reset_postdata();
      ?>
  </div><!-- /.matches__list -->
</div>

<?php get_footer(); ?>
