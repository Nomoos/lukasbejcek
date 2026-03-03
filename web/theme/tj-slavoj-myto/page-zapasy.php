<?php
/**
 * Template Name: Zápasy
 * Stránka se seznamem zápasů TJ Slavoj Mýto.
 * Obsahuje: filtry (tým, sezóna, stav), modrý pruh s logem a seznam karet zápasů.
 */
get_header();

// Filtry z GET parametrů
$filtr_tym   = isset($_GET['tym'])   ? sanitize_text_field(wp_unslash($_GET['tym']))   : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';
$filtr_stav  = isset($_GET['stav'])  ? sanitize_text_field(wp_unslash($_GET['stav']))  : 'vse';

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
?>

<!-- ===== ZÁHLAVÍ SEKCE + FILTRY ===== -->
<div class="container py-5">
    <h2 class="mb-0">Zápasy</h2>
    <p class="text-muted mb-4">Přehled všech zápasů TJ Slavoj Mýto</p>

    <form method="get" class="d-flex gap-3 mb-4 flex-wrap">

        <!-- FILTR: Tým (tmavě modrý) -->
        <select name="tym" class="form-select filter-select-team" onchange="this.form.submit()">
            <option value="">Všechny týmy</option>
            <?php if (!is_wp_error($dostupne_tymy)) : foreach ($dostupne_tymy as $tym_term) : ?>
                <option value="<?php echo esc_attr($tym_term->slug); ?>"
                    <?php selected($filtr_tym, $tym_term->slug); ?>>
                    <?php echo esc_html($tym_term->name); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <!-- FILTR: Sezóna (světle šedý) -->
        <select name="sezona" class="form-select filter-select-season" onchange="this.form.submit()">
            <option value="">Všechny sezóny</option>
            <?php if (!is_wp_error($dostupne_sezony)) : foreach ($dostupne_sezony as $sezona_term) : ?>
                <option value="<?php echo esc_attr($sezona_term->slug); ?>"
                    <?php selected($filtr_sezona, $sezona_term->slug); ?>>
                    Sezóna <?php echo esc_html($sezona_term->name); ?>
                </option>
            <?php endforeach; endif; ?>
        </select>

        <!-- FILTR: Stav (tmavě modrý) -->
        <select name="stav" class="form-select filter-select-status" onchange="this.form.submit()">
            <option value="vse"        <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
            <option value="odehrane"   <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané zápasy</option>
            <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Budoucí zápasy</option>
        </select>

    </form>
</div>

<!-- ===== MODRÝ PRUH S LOGEM ===== -->
<div class="fluid">
    <div class="row g-0">
        <div class="col-5">
            <div class="blue-bar-p"></div>
        </div>
        <div class="col-2 text-center d-flex align-items-center justify-content-center"
             style="background-color:#233D97;">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png"
                 alt="TJ Slavoj Mýto" height="50">
        </div>
        <div class="col-5">
            <div class="blue-bar-l"></div>
        </div>
    </div>
</div>

<?php if ($filtr_tym) : ?>
<div class="container">
    <p class="section-team-title">
        <?php echo esc_html(isset($tymy_nazvy[$filtr_tym]) ? $tymy_nazvy[$filtr_tym] : $filtr_tym); ?>
    </p>
</div>
<?php endif; ?>

<!-- ===== SEZNAM ZÁPASŮ ===== -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <?php
            // Sestavení WP_Query
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

            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            if (!empty($tax_query)) {
                $args['tax_query'] = $tax_query;
            }

            if ($filtr_stav === 'odehrane') {
                $tax_query_stav = array(
                    'taxonomy' => 'stav-zapasu',
                    'field'    => 'slug',
                    'terms'    => 'odehrany',
                );
                $args['tax_query'][] = $tax_query_stav;
            } elseif ($filtr_stav === 'neodehrane') {
                $tax_query_stav = array(
                    'taxonomy' => 'stav-zapasu',
                    'field'    => 'slug',
                    'terms'    => 'nadchazejici',
                );
                $args['tax_query'][] = $tax_query_stav;
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

                    // Formátování data do češtiny (d. m. Y)
                    $datum_formatovany = '';
                    if ($datum) {
                        $ts = strtotime($datum);
                        if ($ts) {
                            $datum_formatovany = date_i18n('j. n. Y', $ts);
                        }
                    }

                    // Stav zápasu a výsledkové třídy
                    $je_odehrany  = !empty($skore);
                    $vysledek     = $je_odehrany ? slavoj_zapas_vysledek($domaci, $hoste, $skore) : '';

                    // CSS třídy pro kartu a skóre
                    $karta_trida = $je_odehrany ? '' : ' match-upcoming';
                    switch ($vysledek) {
                        case 'vyhral':  $skore_trida = 'score-win';  break;
                        case 'prohral': $skore_trida = 'score-loss'; break;
                        case 'remiza':  $skore_trida = 'score-draw'; break;
                        default:        $skore_trida = 'score-upcoming'; break;
                    }

                    // Odznak stavu
                    if ($je_odehrany) {
                        $badge_trida = 'badge-odehrany';
                        $badge_text  = 'Odehráno';
                    } else {
                        $badge_trida = 'badge-nadchazejici';
                        $badge_text  = 'Nadcházející';
                    }

                    // Označení domácí/hosté
                    // Pokud je TJ Slavoj Mýto domácí → zobrazíme "Domácí" vlevo
                    $slavoj_je_domaci = (stripos($domaci, 'Slavoj') !== false);
                    ?>
                    <div class="match-card mb-3 p-3 border rounded-4<?php echo esc_attr($karta_trida); ?>">
                        <div class="row align-items-center">

                            <!-- Datum, čas a odznak -->
                            <div class="col-md-3 small text-muted">
                                <div><?php echo esc_html($datum_formatovany); ?> v <?php echo esc_html($cas); ?></div>
                                <div class="mt-1">
                                    <span class="match-status-badge <?php echo esc_attr($badge_trida); ?>">
                                        <?php echo esc_html($badge_text); ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Týmy a výsledek -->
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-truncate" style="max-width:38%;">
                                        <?php echo esc_html($domaci); ?>
                                    </strong>
                                    <span class="<?php echo esc_attr($skore_trida); ?> px-2">
                                        <?php echo $je_odehrany ? esc_html($skore) : 'vs'; ?>
                                    </span>
                                    <strong class="text-truncate text-end" style="max-width:38%;">
                                        <?php echo esc_html($hoste); ?>
                                    </strong>
                                </div>

                                <!-- Domácí / Hosté + Střelci -->
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span><?php echo $slavoj_je_domaci ? 'Domácí' : 'Hosté'; ?></span>
                                    <span><?php echo $slavoj_je_domaci ? 'Hosté' : 'Domácí'; ?></span>
                                </div>
                                <?php if ($je_odehrany && $strelci) : ?>
                                <div class="small text-secondary mt-1">
                                    Střelci: <?php echo esc_html($strelci); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <?php
                endwhile;
            else :
                ?>
                <div class="match-empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                         class="bi bi-calendar-x mb-3 text-muted" viewBox="0 0 16 16">
                        <path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1
                                 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8
                                 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146
                                 7.854a.5.5 0 0 1 0-.708z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1
                                 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1
                                 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0
                                 0 1-1V4H1z"/>
                    </svg>
                    <p class="mb-1 fw-semibold">Žádné zápasy nebyly nalezeny</p>
                    <p class="small mb-0">Zkuste změnit filtry nebo se vraťte později.</p>
                </div>
                <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
