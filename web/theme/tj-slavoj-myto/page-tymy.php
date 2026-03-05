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

<section class="section">
  <div class="container">
    <header class="page-title">
      <h1 class="page-title__h1"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h1>
      <p class="page-title__subtitle"><?php
          $sub = get_the_excerpt(get_queried_object_id());
          echo $sub ? esc_html(wp_strip_all_tags($sub)) : esc_html(sprintf('Přehled týmů %s', get_bloginfo('name')));
      ?></p>
    </header>

    <!-- FILTRY – selecty odešlou formulář ihned po změně; tlačítko jako záloha bez JS -->
    <form method="get" class="filters" role="search" aria-label="Filtrování týmů">
      <label class="sr-only" for="f-tym">Tým</label>
      <select id="f-tym" name="tym" class="filter filter--primary" onchange="this.form.submit()">
        <option value="">Všechny týmy</option>
        <?php if (!is_wp_error($dostupne_tymy) && !empty($dostupne_tymy)) : foreach ($dostupne_tymy as $t) : ?>
          <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($filtr_tym, $t->slug); ?>>
            <?php echo esc_html($t->name); ?>
          </option>
        <?php endforeach; endif; ?>
      </select>

      <label class="sr-only" for="f-sezona">Sezóna</label>
      <select id="f-sezona" name="sezona" class="filter filter--muted" onchange="this.form.submit()">
        <option value="">Všechny sezóny</option>
        <?php if (!is_wp_error($dostupne_sezony) && !empty($dostupne_sezony)) : foreach ($dostupne_sezony as $s) : ?>
          <option value="<?php echo esc_attr($s->slug); ?>" <?php selected($filtr_sezona, $s->slug); ?>>
            Sezóna <?php echo esc_html($s->name); ?>
          </option>
        <?php endforeach; endif; ?>
      </select>

      <noscript>
        <button type="submit" class="filter filter--submit">Filtrovat</button>
      </noscript>
    </form>
  </div>
</section>

<?php if ($filtr_tym) : ?>
<!-- TÝM HERO -->
<div class="team-hero">
  <div class="team-hero__bar">
    <img class="team-hero__logo"
         src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
         alt="TJ Slavoj Mýto">
  </div>
  <p class="team-hero__title container">
    <?php echo esc_html($nazev_tymu); ?>
    <?php if ($nazev_sezony) : ?>
      <span class="team-hero__season"> – <?php echo esc_html($nazev_sezony); ?></span>
    <?php endif; ?>
  </p>
</div>

<section class="section">
  <div class="container">
    <?php
    // Informace o týmu z CPT 'tym'
    $team_query = new WP_Query(array(
        'post_type'      => 'tym',
        'posts_per_page' => 1,
        'tax_query'      => array(
            array(
                'taxonomy' => 'kategorie-tymu',
                'field'    => 'slug',
                'terms'    => $filtr_tym,
            ),
        ),
    ));

    if ($team_query->have_posts()) :
        $team_query->the_post();
        $pocet_hracu   = esc_html(get_post_meta(get_the_ID(), 'pocet_hracu',     true));
        $hlavni_trener = esc_html(get_post_meta(get_the_ID(), 'hlavni_trener',   true));
        $asistent      = esc_html(get_post_meta(get_the_ID(), 'asistent_trenera', true));
        $zdravotnik    = esc_html(get_post_meta(get_the_ID(), 'zdravotnik',       true));
    ?>
    <!-- INFO BOX -->
    <div class="p-3 border rounded-3 mb-4">
      <div class="row g-3 text-center text-md-start">
        <?php if ($pocet_hracu) : ?>
          <div class="col-6 col-md-3"><strong>Počet hráčů:</strong><br><?php echo $pocet_hracu; ?></div>
        <?php endif; ?>
        <?php if ($hlavni_trener) : ?>
          <div class="col-6 col-md-3"><strong>Hlavní trenér:</strong><br><?php echo $hlavni_trener; ?></div>
        <?php endif; ?>
        <?php if ($asistent) : ?>
          <div class="col-6 col-md-3"><strong>Asistent trenéra:</strong><br><?php echo $asistent; ?></div>
        <?php endif; ?>
        <?php if ($zdravotnik) : ?>
          <div class="col-6 col-md-3"><strong>Zdravotník:</strong><br><?php echo $zdravotnik; ?></div>
        <?php endif; ?>
      </div>
    </div>
    <?php
    endif;
    wp_reset_postdata();
    ?>

    <!-- SOUPISKA HRÁČŮ -->
    <h2 class="h5 fw-bold mb-3">Soupiska hráčů</h2>
    <div class="p-3 border rounded-3">
      <?php slavoj_vypis_hrace_tymu($filtr_tym); ?>
    </div>
  </div>
</section>

<?php else : ?>
<section class="section">
  <div class="container">
    <div class="empty-state">
      <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
           width="48" height="48" fill="none" stroke="currentColor"
           stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
           aria-hidden="true" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
      <p class="empty-state__title">Vyberte tým</p>
      <p class="empty-state__text">Pro zobrazení soupisky a informací o týmu vyberte tým ve filtru výše.</p>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
