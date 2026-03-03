<?php
/**
 * Template Name: Zápasy
 * Přehledová stránka zápasů TJ Slavoj Mýto.
 * Layout:  záhlaví → filtry → team-hero → seznam karet zápasů
 */
get_header();

/* ── Filtry z GET parametrů ── */
$filtr_tym    = isset($_GET['tym'])    ? sanitize_text_field(wp_unslash($_GET['tym']))    : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav   = isset($_GET['stav'])   ? sanitize_text_field(wp_unslash($_GET['stav']))   : 'vse';

/* ── Dynamické načtení možností filtrů ── */
$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'DESC',
));

$dostupne_tymy = get_terms(array(
    'taxonomy'   => 'kategorie-tymu',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
));

/* ── Název pro team-hero ── */
$tym_nazev = $filtr_tym ? slavoj_get_team_display_name($filtr_tym) : '';
?>

<!-- ════════════════════════════════════════
     ZÁHLAVÍ + FILTRY
     ════════════════════════════════════════ -->
<div class="container page-title">
  <h1 class="page-title__h1">Zápasy</h1>
  <p class="page-title__subtitle">Přehled všech zápasů TJ Slavoj Mýto</p>

  <form method="get" class="filters" role="search" aria-label="Filtrování zápasů">

    <label class="sr-only" for="f-tym">Tým</label>
    <select id="f-tym" name="tym" class="filter filter--primary" onchange="this.form.submit()">
      <option value="">Všechny týmy</option>
      <?php if (!is_wp_error($dostupne_tymy)) : foreach ($dostupne_tymy as $t) : ?>
        <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($filtr_tym, $t->slug); ?>>
          <?php echo esc_html($t->name); ?>
        </option>
      <?php endforeach; endif; ?>
    </select>

    <label class="sr-only" for="f-sezona">Sezóna</label>
    <select id="f-sezona" name="sezona" class="filter filter--muted" onchange="this.form.submit()">
      <option value="">Všechny sezóny</option>
      <?php if (!is_wp_error($dostupne_sezony)) : foreach ($dostupne_sezony as $s) : ?>
        <option value="<?php echo esc_attr($s->slug); ?>" <?php selected($filtr_sezona, $s->slug); ?>>
          Sezóna <?php echo esc_html($s->name); ?>
        </option>
      <?php endforeach; endif; ?>
    </select>

    <label class="sr-only" for="f-stav">Stav</label>
    <select id="f-stav" name="stav" class="filter filter--primary" onchange="this.form.submit()">
      <option value="vse"        <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
      <option value="odehrane"   <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané</option>
      <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Nadcházející</option>
    </select>

  </form>
</div>

<!-- ════════════════════════════════════════
     TEAM HERO
     ════════════════════════════════════════ -->
<div class="team-hero">
  <div class="team-hero__bar">
    <img class="team-hero__logo"
         src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
         alt="TJ Slavoj Mýto">
  </div>
  <?php if ($tym_nazev) : ?>
  <p class="team-hero__title container"><?php echo esc_html($tym_nazev); ?></p>
  <?php endif; ?>
</div>

<!-- ════════════════════════════════════════
     SEZNAM ZÁPASŮ
     ════════════════════════════════════════ -->
<section class="matches container" aria-label="Seznam zápasů">
  <div class="matches__list">
    <?php
    /* ── WP_Query ── */
    $args = array(
        'post_type'      => 'zapas',
        'posts_per_page' => 40,
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
        $args['tax_query'] = $tax_query;
    }

    $q = new WP_Query($args);

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

            /* Formát data */
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

            $card_cls  = $je_odehrany ? 'match-card--played' : 'match-card--upcoming';
            if ($vysledek === 'vyhral')  { $card_cls .= ' match-card--win'; }
            if ($vysledek === 'prohral') { $card_cls .= ' match-card--loss'; }
            if ($vysledek === 'remiza')  { $card_cls .= ' match-card--draw'; }

            $score_cls = 'match-card__score';
            if ($je_odehrany) {
                $score_cls .= ' match-card__score--' . ($vysledek === 'vyhral' ? 'win' : ($vysledek === 'prohral' ? 'loss' : 'draw'));
            } else {
                $score_cls .= ' match-card__score--upcoming';
            }

            /* Badge */
            if ($je_odehrany) {
                $badge_cls  = 'badge badge--neutral';
                $badge_text = 'Odehráno';
            } else {
                $badge_cls  = 'badge badge--primary';
                $badge_text = 'Nadcházející';
            }

            /* Domácí / Hosté orientace */
            $slavoj_domaci = slavoj_is_club_team($domaci);
            $lbl_levy  = $slavoj_domaci ? 'Domácí' : 'Hosté';
            $lbl_pravy = $slavoj_domaci ? 'Hosté'  : 'Domácí';

            /* Extra třída pro vlastní tým */
            $home_cls = 'match-card__team match-card__team--home' . ($slavoj_domaci ? ' match-card__team--slavoj' : '');
            $away_cls = 'match-card__team match-card__team--away' . (!$slavoj_domaci && slavoj_is_club_team($hoste) ? ' match-card__team--slavoj' : '');
            ?>

            <article class="match-card <?php echo esc_attr($card_cls); ?>"
                     aria-label="<?php echo esc_attr(sprintf('%s vs %s', $domaci, $hoste)); ?>">

              <!-- META: datum + čas + odznak -->
              <div class="match-card__meta">
                <span class="match-card__datetime">
                  <?php if ($datum_fmt) : ?>
                    <time datetime="<?php echo esc_attr($datum); ?>"><?php echo esc_html($datum_fmt); ?></time>
                  <?php endif; ?>
                  <?php if ($cas) : ?>
                    &nbsp;v&nbsp;<?php echo esc_html($cas); ?>
                  <?php endif; ?>
                </span>
                <span class="<?php echo esc_attr($badge_cls); ?>"><?php echo esc_html($badge_text); ?></span>
              </div>

              <!-- HLAVNÍ: tým – skóre – tým
                   (na mobilu: skóre nahoře díky order:-1 v CSS) -->
              <div class="match-card__main">
                <span class="<?php echo esc_attr($home_cls); ?>">
                  <?php echo esc_html($domaci); ?>
                </span>
                <span class="<?php echo esc_attr($score_cls); ?>"
                      aria-label="Skóre <?php echo $je_odehrany ? esc_attr($skore) : 'vs'; ?>">
                  <?php echo $je_odehrany ? esc_html($skore) : 'vs'; ?>
                </span>
                <span class="<?php echo esc_attr($away_cls); ?>">
                  <?php echo esc_html($hoste); ?>
                </span>
              </div>

              <!-- SUB: popisky stran -->
              <div class="match-card__sub">
                <span class="match-card__side"><?php echo esc_html($lbl_levy); ?></span>
                <span class="match-card__side"><?php echo esc_html($lbl_pravy); ?></span>
              </div>

              <!-- STŘELCI (jen odehrané se střelci) -->
              <?php if ($je_odehrany && $strelci) : ?>
              <div class="match-card__scorers">
                ⚽ <?php echo esc_html($strelci); ?>
              </div>
              <?php endif; ?>

            </article>
            <?php
        endwhile;
    else :
        ?>
        <div class="empty-state" role="status">
          <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
               width="48" height="48" fill="none"
               stroke="currentColor" stroke-width="1.5"
               stroke-linecap="round" stroke-linejoin="round"
               aria-hidden="true" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2"  x2="16" y2="6"/>
            <line x1="8"  y1="2"  x2="8"  y2="6"/>
            <line x1="3"  y1="10" x2="21" y2="10"/>
            <line x1="10" y1="15" x2="14" y2="19"/>
            <line x1="14" y1="15" x2="10" y2="19"/>
          </svg>
          <p class="empty-state__title">Žádné zápasy nenalezeny</p>
          <p class="empty-state__text">Zkuste změnit filtry nebo se vraťte později.</p>
        </div>
        <?php
    endif;
    wp_reset_postdata();
    ?>
  </div><!-- /.matches__list -->
</section>

<?php get_footer(); ?>
