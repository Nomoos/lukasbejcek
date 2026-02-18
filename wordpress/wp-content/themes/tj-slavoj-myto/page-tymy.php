<?php
/**
 * Template Name: Týmy
 * Stránka se soupiskami týmů TJ Slavoj Mýto
 * Obsahuje: filtry (tým, sezóna), informace o týmu, soupiska hráčů
 */
get_header();

// Získání filtrů z GET parametrů
$filtr_tym = isset($_GET['tym']) ? sanitize_text_field(wp_unslash($_GET['tym'])) : 'muzi-a';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '2025-26';

$tymy_nazvy = array(
    'muzi-a'      => 'Muži A',
    'muzi-b'      => 'Muži B',
    'dorost'      => 'Dorost',
    'starsi-zaci' => 'Starší žáci',
);

$sezony_nazvy = array(
    '2025-26' => 'Sezóna 2025/26',
    '2024-25' => 'Sezóna 2024/25',
);

$nazev_tymu = isset($tymy_nazvy[$filtr_tym]) ? $tymy_nazvy[$filtr_tym] : $filtr_tym;
$nazev_sezony = isset($sezony_nazvy[$filtr_sezona]) ? $sezony_nazvy[$filtr_sezona] : $filtr_sezona;
?>

<!-- OBSAH -->
<div class="container py-5">
    <h2 class="mb-0">Týmy</h2>
    <p class="text-muted mb-4">Přehled všech týmů TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <form method="get" class="d-flex gap-3 mb-4">
      <select name="tym" class="form-select bg-light" style="width: 11%;" onchange="this.form.submit()">
        <?php foreach ($tymy_nazvy as $slug => $nazev) : ?>
          <option value="<?php echo esc_attr($slug); ?>" <?php selected($filtr_tym, $slug); ?>>
            <?php echo esc_html($nazev); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="sezona" class="form-select bg-light" style="width: 15%;" onchange="this.form.submit()">
        <?php foreach ($sezony_nazvy as $slug => $nazev) : ?>
          <option value="<?php echo esc_attr($slug); ?>" <?php selected($filtr_sezona, $slug); ?>>
            <?php echo esc_html($nazev); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <div class="row">
      <div class="col-md-8">
        <h4 class="fw-bold"><?php echo esc_html($nazev_tymu); ?></h4>
        <p class="text-muted"><?php echo esc_html($nazev_sezony); ?></p>

        <!-- INFO BOX -->
        <?php
        // Získání metadat o týmu z příspěvku kategorie 'tymy'
        $team_args = array(
            'category_name'  => 'tymy',
            'posts_per_page' => 1,
            'meta_query'     => array(
                array(
                    'key'     => 'tym_slug',
                    'value'   => $filtr_tym,
                    'compare' => '=',
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
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" class="img-fluid mt-4" style="max-width: 200px;">
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
