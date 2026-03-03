<?php
/**
 * Template Name: Zápasy
 * Stránka se seznamem zápasů TJ Slavoj Mýto.
 * Obsahuje: filtry (tým, sezóna, stav), team-hero pruh a seznam karet zápasů.
 */
get_header();

// Filtry z GET parametrů
$filtr_tym    = isset($_GET['tym'])    ? sanitize_text_field(wp_unslash($_GET['tym']))    : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav   = isset($_GET['stav'])   ? sanitize_text_field(wp_unslash($_GET['stav']))   : 'vse';

// Dynamické načtení termínů sezón z databáze
$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'DESC',
));

// Dynamické načtení kategorií týmů (jen ty s přiřazenými zápasy)
$dostupne_tymy = get_terms(array(
    'taxonomy'   => 'kategorie-tymu',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
));

// Mapa slug → zobrazovaný název pro záhlaví sekce
$tymy_nazvy = array(
    'muzi-a'      => 'Muži A',
    'muzi-b'      => 'Muži B',
    'dorost'      => 'Dorost',
    'starsi-zaci' => 'Starší žáci',
    'mladsi-zaci' => 'Mladší žáci',
    'pripravka'   => 'Přípravka',
);

// Aktuálně vybraný název týmu (pro hero)
$tym_nazev_zobrazeny = $filtr_tym && isset($tymy_nazvy[$filtr_tym])
    ? $tymy_nazvy[$filtr_tym]
    : '';
?>

<!-- ===== ZÁHLAVÍ STRÁNKY ===== -->
<div class="container page-title">
    <h1 class="page-title__h1">Zápasy</h1>
    <p class="page-title__subtitle">Přehled všech zápasů TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <form method="get" class="filters" role="search" aria-label="Filtrování zápasů">

        <label class="sr-only" for="filter-tym">Tým</label>
        <select id="filter-tym" name="tym"
                class="filter filter--primary"
                onchange="this.form.submit()">
            <option value="">Všechny týmy</option>
            <?php if (!is_wp_error($dostupne_tymy)) : foreach ($dostupne_tymy as $tym_term) : ?>
                <option value="<?php echo esc_attr($tym_term->slug); ?>"
                    <?php selected($filtr_tym, $tym_term->slug); ?>>
                    <?php echo esc_html($tym_term->name); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <label class="sr-only" for="filter-sezona">Sezóna</label>
        <select id="filter-sezona" name="sezona"
                class="filter filter--muted"
                onchange="this.form.submit()">
            <option value="">Všechny sezóny</option>
            <?php if (!is_wp_error($dostupne_sezony)) : foreach ($dostupne_sezony as $sezona_term) : ?>
                <option value="<?php echo esc_attr($sezona_term->slug); ?>"
                    <?php selected($filtr_sezona, $sezona_term->slug); ?>>
                    Sezóna <?php echo esc_html($sezona_term->name); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <label class="sr-only" for="filter-stav">Stav zápasů</label>
        <select id="filter-stav" name="stav"
                class="filter filter--primary"
                onchange="this.form.submit()">
            <option value="vse"        <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
            <option value="odehrane"   <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané zápasy</option>
            <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Budoucí zápasy</option>
        </select>

    </form>
</div>

<!-- ===== TEAM HERO – modrý pruh s logem a názvem týmu ===== -->
<div class="team-hero">
    <div class="team-hero__bar">
        <img class="team-hero__logo"
             src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
             alt="TJ Slavoj Mýto">
    </div>
    <?php if ($tym_nazev_zobrazeny) : ?>
    <p class="team-hero__title container"><?php echo esc_html($tym_nazev_zobrazeny); ?></p>
    <?php endif; ?>
</div>

<!-- ===== SEZNAM ZÁPASŮ ===== -->
<section class="matches container" aria-label="Seznam zápasů">
    <div class="matches__list">
        <?php
        // ──────────────────────────────────────────────────────────
        // Sestavení WP_Query
        // ──────────────────────────────────────────────────────────
        $args = array(
            'post_type'      => 'zapas',
            'posts_per_page' => 30,
            'meta_key'       => 'datum_zapasu',
            'orderby'        => 'meta_value',
            'order'          => 'DESC',
        );

        $tax_query = array();

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
            $tax_query['relation'] = 'AND';
        }
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        $zapasy_query = new WP_Query($args);

        if ($zapasy_query->have_posts()) :
            while ($zapasy_query->have_posts()) :
                $zapasy_query->the_post();
                $post_id = get_the_ID();

                $datum   = get_post_meta($post_id, 'datum_zapasu', true);
                $cas     = get_post_meta($post_id, 'cas_zapasu', true);
                $domaci  = get_post_meta($post_id, 'domaci', true);
                $hoste   = get_post_meta($post_id, 'hoste', true);
                $skore   = get_post_meta($post_id, 'skore', true);
                $strelci = get_post_meta($post_id, 'strelci', true);

                // Formátování data do češtiny (j. n. Y)
                $datum_formatovany = '';
                if ($datum) {
                    $ts = strtotime($datum);
                    if ($ts) {
                        $datum_formatovany = date_i18n('j. n. Y', $ts);
                    }
                }

                // Stav a výsledek
                $je_odehrany = !empty($skore);
                $vysledek    = $je_odehrany ? slavoj_zapas_vysledek($domaci, $hoste, $skore) : '';

                // CSS modifikátory karty
                $card_mods = $je_odehrany ? 'match-card--played' : 'match-card--upcoming';
                if ($vysledek === 'vyhral')  { $card_mods .= ' match-card--win'; }
                if ($vysledek === 'prohral') { $card_mods .= ' match-card--loss'; }
                if ($vysledek === 'remiza')  { $card_mods .= ' match-card--draw'; }

                // CSS modifikátor skóre
                $score_mod = 'match-card__score--upcoming';
                if ($je_odehrany) {
                    switch ($vysledek) {
                        case 'vyhral':  $score_mod = 'match-card__score--win';  break;
                        case 'prohral': $score_mod = 'match-card__score--loss'; break;
                        default:        $score_mod = 'match-card__score--draw'; break;
                    }
                }

                // Badge stavu
                if ($je_odehrany) {
                    $badge_mod  = 'badge--neutral';
                    $badge_text = 'Odehráno';
                } else {
                    $badge_mod  = 'badge--primary';
                    $badge_text = 'Nadcházející';
                }

                // Domácí / hosté orientace z pohledu TJ Slavoj Mýto
                $slavoj_je_domaci = (stripos($domaci, 'Slavoj') !== false);
                $label_levy  = $slavoj_je_domaci ? 'Domácí' : 'Hosté';
                $label_pravy = $slavoj_je_domaci ? 'Hosté'  : 'Domácí';
                ?>

                <article class="match-card <?php echo esc_attr($card_mods); ?>"
                         aria-label="<?php echo esc_attr($domaci . ' vs ' . $hoste); ?>">

                    <!-- Meta: datum + čas + odznak -->
                    <div class="match-card__meta">
                        <span class="match-card__datetime">
                            <?php echo esc_html($datum_formatovany); ?><?php if ($cas) : ?> v <?php echo esc_html($cas); ?><?php endif; ?>
                        </span>
                        <span class="badge <?php echo esc_attr($badge_mod); ?>">
                            <?php echo esc_html($badge_text); ?>
                        </span>
                    </div>

                    <!-- Hlavní řádek: domácí – skóre – hosté -->
                    <div class="match-card__main">
                        <span class="match-card__team match-card__team--home">
                            <?php echo esc_html($domaci); ?>
                        </span>
                        <span class="match-card__score <?php echo esc_attr($score_mod); ?>"
                              aria-label="Skóre: <?php echo $je_odehrany ? esc_attr($skore) : 'nevyhodnoceno'; ?>">
                            <?php echo $je_odehrany ? esc_html($skore) : 'vs'; ?>
                        </span>
                        <span class="match-card__team match-card__team--away">
                            <?php echo esc_html($hoste); ?>
                        </span>
                    </div>

                    <!-- Sub: popisky stran -->
                    <div class="match-card__sub">
                        <span class="match-card__side"><?php echo esc_html($label_levy); ?></span>
                        <span class="match-card__side"><?php echo esc_html($label_pravy); ?></span>
                    </div>

                    <!-- Střelci (jen odehrané zápasy s daty) -->
                    <?php if ($je_odehrany && $strelci) : ?>
                    <div class="match-card__scorers">
                        Střelci: <?php echo esc_html($strelci); ?>
                    </div>
                    <?php endif; ?>

                </article>
                <?php
            endwhile;
        else :
            ?>
            <div class="empty-state" role="alert">
                <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
                     width="44" height="44" fill="currentColor" viewBox="0 0 16 16"
                     aria-hidden="true">
                    <path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1
                             .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8
                             9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146
                             7.854a.5.5 0 0 1 0-.708z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1
                             2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1
                             2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0
                             0 1-1V4H1z"/>
                </svg>
                <p class="empty-state__title">Žádné zápasy nebyly nalezeny</p>
                <p class="empty-state__text">Zkuste změnit filtry nebo se vraťte později.</p>
            </div>
            <?php
        endif;
        wp_reset_postdata();
        ?>
    </div><!-- /.matches__list -->
</section>

<?php get_footer(); ?>
