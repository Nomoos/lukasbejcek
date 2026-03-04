<?php
/**
 * Template Name: Zápasy
 * Přehledová stránka zápasů TJ Slavoj Mýto.
 *
 * Přístup: PHP/HTML/CSS primary – žádný JavaScript.
 * Filtry jsou standardní HTML <form> (GET), výsledky renderuje PHP smyčka.
 */
get_header();

/* ── Sanitace GET parametrů ── */
$filtr_tym    = isset($_GET['tym'])    ? sanitize_text_field(wp_unslash($_GET['tym']))    : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav   = isset($_GET['stav'])   ? sanitize_text_field(wp_unslash($_GET['stav']))   : 'vse';
$paged        = isset($_GET['stranka']) ? max(1, absint($_GET['stranka'])) : 1;

/* ── Taxonomie pro filtry ── */
$dostupne_tymy   = get_terms(array(
    'taxonomy'   => 'kategorie-tymu',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
));
$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'DESC',
));

/* ── Název týmu pro hero sekci ── */
$tym_nazev = $filtr_tym ? slavoj_get_team_display_name($filtr_tym) : '';
?>

<!-- ═════════════════════════════════
     ZÁHLAVÍ STRÁNKY + FILTRY
     ═════════════════════════════════ -->
<section class="section">
  <div class="container">

    <header class="page-title">
      <h1 class="page-title__h1"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h1>
      <p class="page-title__subtitle"><?php
          $sub = get_the_excerpt(get_queried_object_id());
          echo $sub ? esc_html(wp_strip_all_tags($sub)) : esc_html(sprintf('Přehled zápasů %s', get_bloginfo('name')));
      ?></p>
    </header>

    <?php
    get_template_part('template-parts/filters', 'matches', array(
        'dostupne_tymy'   => $dostupne_tymy,
        'dostupne_sezony' => $dostupne_sezony,
        'filtr_tym'       => $filtr_tym,
        'filtr_sezona'    => $filtr_sezona,
        'filtr_stav'      => $filtr_stav,
    ));
    ?>

  </div>
</section>

<!-- ═════════════════════════════════
     TEAM HERO
     ═════════════════════════════════ -->
<?php
get_template_part('template-parts/hero', 'team', array(
    'tym_nazev' => $tym_nazev,
));
?>

<!-- ═════════════════════════════════
     SEZNAM ZÁPASŮ
     ═════════════════════════════════ -->
<section class="section matches" aria-label="Seznam zápasů">
  <div class="container">
    <div class="matches__list stack">
      <?php
      /* ── WP_Query ── */
      $query_args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 10,
          'paged'          => $paged,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'DESC',
      );

      $tax_query = array('relation' => 'AND');

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
      if ($filtr_stav === 'odehrane') {
          $tax_query[] = array(
              'taxonomy' => 'stav-zapasu',
              'field'    => 'slug',
              'terms'    => 'odehrany',
          );
      } elseif ($filtr_stav === 'neodehrane') {
          $tax_query[] = array(
              'taxonomy' => 'stav-zapasu',
              'field'    => 'slug',
              'terms'    => 'nadchazejici',
          );
      }

      if (count($tax_query) > 1) {
          $query_args['tax_query'] = $tax_query;
      }

      $q = new WP_Query($query_args);

      if ($q->have_posts()) :
          while ($q->have_posts()) :
              $q->the_post();
              $id = get_the_ID();

              $datum   = get_post_meta($id, 'datum_zapasu', true);
              $cas     = get_post_meta($id, 'cas_zapasu',   true);
              $domaci  = get_post_meta($id, 'domaci',       true);
              $hoste   = get_post_meta($id, 'hoste',        true);
              $skore   = get_post_meta($id, 'skore',        true);
              $strelci = get_post_meta($id, 'strelci',      true);

              /* Formátování data */
              $datum_fmt = '';
              if ($datum) {
                  $ts = strtotime($datum);
                  if ($ts) {
                      $datum_fmt = date_i18n('j. n. Y', $ts);
                  }
              }

              /* Výsledek a CSS modifikátory */
              $je_odehrany = !empty($skore);
              $vysledek    = $je_odehrany ? slavoj_zapas_vysledek($domaci, $hoste, $skore) : '';

              $card_cls = $je_odehrany ? 'match-card--played' : 'match-card--upcoming';
              if ($vysledek === 'vyhral')  { $card_cls .= ' match-card--win'; }
              if ($vysledek === 'prohral') { $card_cls .= ' match-card--loss'; }
              if ($vysledek === 'remiza')  { $card_cls .= ' match-card--draw'; }

              $score_cls = 'match-card__score';
              if ($je_odehrany) {
                  if ($vysledek === 'vyhral')  { $score_cls .= ' match-card__score--win'; }
                  elseif ($vysledek === 'prohral') { $score_cls .= ' match-card__score--loss'; }
                  else                             { $score_cls .= ' match-card__score--draw'; }
              } else {
                  $score_cls .= ' match-card__score--upcoming';
              }

              /* Odznak stavu */
              if ($je_odehrany) {
                  $badge_cls  = 'badge--neutral';
                  $badge_text = 'Odehráno';
              } else {
                  $badge_cls  = 'badge--primary';
                  $badge_text = 'Nadcházející';
              }

              /* Orientace Domácí / Hosté */
              $slavoj_domaci = slavoj_is_club_team($domaci);
              $lbl_levy      = $slavoj_domaci ? 'Domácí' : 'Hosté';
              $lbl_pravy     = $slavoj_domaci ? 'Hosté'  : 'Domácí';

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
                  'lbl_levy'   => $lbl_levy,
                  'lbl_pravy'  => $lbl_pravy,
                  'home_cls'   => $home_cls,
                  'away_cls'   => $away_cls,
              ));

          endwhile;
          wp_reset_postdata();

          /* ── Stránkování ── */
          $total_pages = $q->max_num_pages;
          if ($total_pages > 1) :
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
              if ($pagination) :
                  echo '<nav class="pagination-nav" aria-label="' . esc_attr__('Stránkování', 'tj-slavoj-myto') . '">' . wp_kses_post($pagination) . '</nav>';
              endif;
          endif;

      else :
          ?>
          <div class="empty-state" role="status">
            <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
                 width="48" height="48" fill="none" stroke="currentColor"
                 stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true" viewBox="0 0 24 24">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8"  y1="2" x2="8"  y2="6"/>
              <line x1="3"  y1="10" x2="21" y2="10"/>
              <line x1="10" y1="15" x2="14" y2="19"/>
              <line x1="14" y1="15" x2="10" y2="19"/>
            </svg>
            <p class="empty-state__title">Žádné zápasy nenalezeny</p>
            <p class="empty-state__text">Zkuste změnit filtry nebo se vraťte později.</p>
          </div>
          <?php
          wp_reset_postdata();
      endif;
      ?>
    </div><!-- /.matches__list -->
  </div>
</section>

<?php get_footer(); ?>
