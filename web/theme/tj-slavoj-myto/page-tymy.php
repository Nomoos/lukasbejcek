<?php
/**
 * Template Name: Týmy
 * Stránka se soupiskami týmů TJ Slavoj Mýto
 * Obsahuje: filtry (tým, sezóna), informace o týmu, soupiska hráčů
 */
get_header();

// Získání filtrů z GET parametrů
$filtr_tym    = isset($_GET['tym'])    ? sanitize_text_field(wp_unslash($_GET['tym']))    : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';

$dostupne_tymy   = get_terms(array(
    'taxonomy'   => 'kategorie-tymu',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
));
$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'DESC',
));

// Název vybraného týmu a sezóny odvozený z taxonomie
$nazev_tymu   = '';
$nazev_sezony = '';
if ($filtr_tym && !is_wp_error($dostupne_tymy)) {
    foreach ($dostupne_tymy as $t) {
        if ($t->slug === $filtr_tym) { $nazev_tymu = $t->name; break; }
    }
}
if ($filtr_sezona && !is_wp_error($dostupne_sezony)) {
    foreach ($dostupne_sezony as $s) {
        if ($s->slug === $filtr_sezona) { $nazev_sezony = 'Sezóna ' . $s->name; break; }
    }
}
?>

<!-- OBSAH -->
<div class="container py-5">
    <h2 class="mb-0">Týmy</h2>
    <p class="text-muted mb-4">Přehled všech týmů TJ Slavoj Mýto</p>

    <!-- FILTRY – selecty odešlou formulář ihned po změně; tlačítko jako záloha bez JS -->
    <form method="get" class="d-flex gap-3 mb-4 flex-wrap">
      <label class="sr-only" for="f-tym">Tým</label>
      <select id="f-tym" name="tym" class="form-select bg-light filter-select-team-sm" onchange="this.form.submit()">
        <option value="">Všechny týmy</option>
        <?php if (!is_wp_error($dostupne_tymy) && !empty($dostupne_tymy)) : foreach ($dostupne_tymy as $t) : ?>
          <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($filtr_tym, $t->slug); ?>>
            <?php echo esc_html($t->name); ?>
          </option>
        <?php endforeach; endif; ?>
      </select>

      <label class="sr-only" for="f-sezona">Sezóna</label>
      <select id="f-sezona" name="sezona" class="form-select bg-light filter-select-season-sm" onchange="this.form.submit()">
        <option value="">Všechny sezóny</option>
        <?php if (!is_wp_error($dostupne_sezony) && !empty($dostupne_sezony)) : foreach ($dostupne_sezony as $s) : ?>
          <option value="<?php echo esc_attr($s->slug); ?>" <?php selected($filtr_sezona, $s->slug); ?>>
            Sezóna <?php echo esc_html($s->name); ?>
          </option>
        <?php endforeach; endif; ?>
      </select>

      <button type="submit" class="btn btn-primary">Filtrovat</button>
    </form>

    <div class="row">
      <div class="col-md-8">
        <h4 class="fw-bold"><?php echo esc_html($nazev_tymu); ?></h4>
        <p class="text-muted"><?php echo esc_html($nazev_sezony); ?></p>

        <!-- INFO BOX -->
        <?php
        // Získání metadat o týmu z CPT 'tym'
        $team_args = array(
            'post_type'      => 'tym',
            'posts_per_page' => 1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'kategorie-tymu',
                    'field'    => 'slug',
                    'terms'    => $filtr_tym,
                ),
            ),
        );
        $team_query = new WP_Query($team_args);

        if ($team_query->have_posts()) :
            $team_query->the_post();
            $pocet_hracu = esc_html(get_post_meta(get_the_ID(), 'pocet_hracu', true));
            $hlavni_trener = esc_html(get_post_meta(get_the_ID(), 'hlavni_trener', true));
            $asistent = esc_html(get_post_meta(get_the_ID(), 'asistent_trenera', true));
            $zdravotnik = esc_html(get_post_meta(get_the_ID(), 'zdravotnik', true));
        ?>
        <div class="p-3 border rounded-3 mb-4">
          <div class="row">
            <div class="col-md-3"><strong>Počet hráčů:</strong><br><?php echo $pocet_hracu; ?></div>
            <div class="col-md-3"><strong>Hlavní trenér:</strong><br><?php echo $hlavni_trener; ?></div>
            <div class="col-md-3"><strong>Asistent trenéra:</strong><br><?php echo $asistent; ?></div>
            <div class="col-md-3"><strong>Zdravotník:</strong><br><?php echo $zdravotnik; ?></div>
          </div>
        </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>
      </div>

      <div class="col-md-4 text-center">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" class="img-fluid club-logo mt-4">
      </div>
    </div>

    <!-- SOUPISKA HRÁČŮ -->
    <div class="row">
      <div class="col-md-12">
        <h5 class="fw-bold mb-3">Soupiska hráčů</h5>

        <div class="p-3 border rounded-3">
          <p class="fw-semibold mb-2">Brankáři</p>
          <div class="row mb-3">
            <?php slavoj_vypis_soupisku('brankari'); ?>
          </div>

          <p class="fw-semibold mb-2">Hráči</p>
          <div class="row gy-2">
            <?php slavoj_vypis_soupisku('hraci'); ?>
          </div>
        </div>
      </div>
    </div>
</div>

<?php get_footer(); ?>
