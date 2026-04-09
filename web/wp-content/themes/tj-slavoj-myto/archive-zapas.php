<?php
/**
 * archive-zapas.php — ARCHIV (VÝPIS) ZÁPASŮ
 * =============================================
 * Zobrazuje seznam všech zápasů s filtrováním (tým, sezóna, stav).
 *
 * WordPress tuto šablonu použije na URL: /zapas/ (archiv CPT 'zapas').
 * archive-{post_type}.php má přednost před archive.php.
 *
 * NOVÉ KONCEPTY:
 * - GET parametry a sanitizace vstupu od uživatele
 * - get_terms() — načtení termů taxonomie pro select boxy
 * - Dynamické sestavování tax_query a meta_query
 * - sanitize_text_field() + wp_unslash() — bezpečné čtení GET dat
 * - onchange="this.form.submit()" — auto-submit formuláře po změně selectu
 */

get_header();

/**
 * ČTENÍ FILTRŮ Z URL (GET parametry)
 *
 * Uživatel vybere filtr v <select> → formulář se odešle (GET) → URL se změní:
 *   /zapas/?kat=muzi-a&sezona=2024-25&stav=vse
 *
 * $_GET = PHP superglobální pole obsahující parametry z URL.
 *
 * BEZPEČNOST VSTUPU:
 * wp_unslash()          = odstraní zpětná lomítka (WordPress je automaticky přidává)
 * sanitize_text_field() = vyčistí text od HTML tagů a nebezpečných znaků
 *
 * Výchozí hodnoty (pokud URL neobsahuje parametr):
 *   tým = 'muzi-a', sezóna = nejnovější, stav = 'vse'
 */
$filtr_tym    = isset($_GET['kat'])     ? sanitize_text_field(wp_unslash($_GET['kat']))    : 'muzi-a';
$filtr_sezona = isset($_GET['sezona'])  ? sanitize_text_field(wp_unslash($_GET['sezona'])) : slavoj_get_latest_sezona_slug();
$filtr_stav   = isset($_GET['stav'])    ? sanitize_text_field(wp_unslash($_GET['stav']))   : 'vse';
$paged        = isset($_GET['stranka']) ? max(1, absint($_GET['stranka']))                 : 1;

/**
 * NAČTENÍ TERMŮ PRO FILTRY
 *
 * get_terms() = načte všechny termy (hodnoty) dané taxonomie z databáze.
 * Vrací pole objektů (každý má: name, slug, term_id, count...).
 *
 * 'hide_empty' => true  = jen termy, které mají přiřazené příspěvky
 * 'hide_empty' => false = i prázdné termy (žádný přiřazený příspěvek)
 *
 * slavoj_sort_tymy() = vlastní helper funkce z functions.php,
 *   řadí kategorie týmů v kanonickém pořadí (Muži A → B → Dorost → ...)
 *
 * array_filter() = odfiltruje prvky pole podle podmínky
 * fn($t) => $t->slug !== 'stara-garda' = arrow function (PHP 7.4+),
 *   vyloučí Starou gardu ze select boxu (ta má zápasy jen v galerii)
 */
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

  <!-- FORMULÁŘ S FILTRY — 3 selecty na jednom řádku na desktopu
       method="get" = odešle data jako URL parametry (?kat=muzi-a&sezona=2024-25)
       action = URL kam se formulář odešle (archiv zápasů)
       Každý <select> má onchange="this.form.submit()" = automaticky odešle
       formulář po změně výběru (bez tlačítka) -->
  <form method="get" action="<?php echo esc_url(get_post_type_archive_link('zapas')); ?>" aria-label="Filtrování zápasů">
    <div class="row g-2 mb-4">

      <div class="col-12 col-md-4">
        <!-- visually-hidden = Bootstrap: skryje vizuálně, ale čtečky ho čtou
             (přístupnost: každý input MUSÍ mít label) -->
        <label class="visually-hidden" for="f-kat">Tým</label>
        <select id="f-kat" name="kat" class="form-select filter-select-team" onchange="this.form.submit()">
          <?php if (!is_wp_error($kategorie)) : foreach ($kategorie as $kat) : ?>
            <!-- selected() = WP helper: vypíše 'selected="selected"'
                 pokud se hodnoty shodují → zvýrazní aktuální výběr -->
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
      /**
       * DYNAMICKÉ SESTAVOVÁNÍ WP_Query
       * ================================
       * Na rozdíl od front-page.php, kde jsou parametry pevné,
       * tady sestavujeme tax_query a meta_query PODMÍNĚNĚ
       * podle toho, co uživatel vybral ve filtrech.
       */
      $args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 10,
          'paged'          => $paged,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
      );

      // Pole pro tax_query a meta_query — naplní se jen pokud je filtr aktivní
      $tax_query  = array();
      $meta_query = array();

      // Přidej filtr týmu (pokud je vybraný)
      if ($filtr_tym) {
          $tax_query[] = array(
              'taxonomy' => 'kategorie-tymu',
              'field'    => 'slug',
              'terms'    => $filtr_tym,
          );
      }

      // Přidej filtr sezóny
      if ($filtr_sezona) {
          $tax_query[] = array(
              'taxonomy' => 'sezona',
              'field'    => 'slug',
              'terms'    => $filtr_sezona,
          );
      }

      // Spoj tax_query s operátorem AND (příspěvek musí splnit oba filtry)
      if (!empty($tax_query)) {
          $tax_query['relation'] = 'AND';
          $args['tax_query'] = $tax_query;
      }

      /**
       * Filtr stavu — porovnává datum_zapasu s dnešním datem.
       * current_time('Y-m-d') = dnešní datum v časové zóně WP
       *
       * 'odehrane'   → datum < dnes (zápas už proběhl)
       * 'neodehrane' → datum >= dnes (zápas teprve bude)
       * 'vse'        → žádný meta_query (všechny zápasy)
       */
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

              // Načtení všech meta polí zápasu
              $id      = get_the_ID();
              $datum   = get_post_meta($id, 'datum_zapasu', true);
              $cas     = get_post_meta($id, 'cas_zapasu',   true);
              $domaci  = get_post_meta($id, 'domaci',       true);
              $hoste   = get_post_meta($id, 'hoste',        true);
              $skore   = get_post_meta($id, 'skore',        true);
              $strelci = get_post_meta($id, 'strelci',      true);

              /**
               * LOGIKA VÝSLEDKU — připraví CSS třídy podle stavu zápasu.
               *
               * slavoj_zapas_vysledek() = helper z functions.php,
               *   vrací 'vyhral', 'prohral' nebo 'remiza'
               * slavoj_is_club_team() = zjistí, zda je název tým Slavoje
               *   (pro zvýraznění "našeho" týmu v kartě)
               */
              $je_odehrany   = !empty($skore);
              $vysledek      = $je_odehrany ? slavoj_zapas_vysledek($domaci, $hoste, $skore) : '';
              $slavoj_domaci = slavoj_is_club_team($domaci);

              // Formátování data do českého formátu
              $datum_fmt = '';
              if ($datum) {
                  $ts = strtotime($datum);
                  if ($ts) $datum_fmt = date_i18n('j. n. Y', $ts);
              }

              // CSS třídy pro kartu — BEM modifikátory podle výsledku
              // BEM = Block__Element--Modifier (CSS pojmenování)
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

              /**
               * get_template_part() s TŘETÍM PARAMETREM ($args)
               * = předání dat do template-parts souboru.
               *
               * Uvnitř card-match.php jsou tyto hodnoty dostupné v poli $args.
               * Takhle se v WP předávají data do znovupoužitelných komponent.
               */
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
