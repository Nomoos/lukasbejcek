<?php
/**
 * Template Name: Galerie
 * Stránka s fotoalbami TJ Slavoj Mýto
 * Obsahuje: filtry (tým, sezóna), mřížka fotoalb s náhledem a názvem
 */
get_header();

// Získání filtrů z GET parametrů
$filtr_tym = isset($_GET['tym']) ? sanitize_text_field(wp_unslash($_GET['tym'])) : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';

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
?>

<div class="container py-5">
    <h2 class="mb-0">Galerie</h2>
    <p class="text-muted mb-4">Fotografie z klubového života TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <form method="get" class="d-flex gap-3 mb-4">
      <select name="tym" class="form-select bg-light filter-select-team-sm" onchange="this.form.submit()">
        <option value="">Všechny týmy</option>
        <?php foreach ($tymy_nazvy as $slug => $nazev) : ?>
          <option value="<?php echo esc_attr($slug); ?>" <?php selected($filtr_tym, $slug); ?>>
            <?php echo esc_html($nazev); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="sezona" class="form-select bg-light filter-select-season-sm" onchange="this.form.submit()">
        <option value="">Všechny sezóny</option>
        <?php foreach ($sezony_nazvy as $slug => $nazev) : ?>
          <option value="<?php echo esc_attr($slug); ?>" <?php selected($filtr_sezona, $slug); ?>>
            <?php echo esc_html($nazev); ?>
          </option>
        <?php endforeach; ?>
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
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" class="gallery-logo">
    </div>
    <div class="col-6">
      <div class="blue-bar-l"></div>
    </div>
  </div>
</div>

<!-- GRID GALERIE -->
<div class="container py-5">
  <div class="row g-4">
    <?php
    $args = array(
        'post_type'      => 'galerie',
        'posts_per_page' => -1,
    );

    // Taxonomy query pro filtrování
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

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'AND';
        $args['tax_query'] = $tax_query;
    }

    $galerie_query = new WP_Query($args);

    if ($galerie_query->have_posts()) :
        while ($galerie_query->have_posts()) :
            $galerie_query->the_post();
            ?>
            <div class="col-6 col-md-3 text-center">
              <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('medium', array(
                      'class' => 'img-fluid gallery-img',
                  )); ?>
                <?php else : ?>
                  <div class="gallery-card mb-2"></div>
                <?php endif; ?>
                <p class="mt-2 text-dark"><?php the_title(); ?></p>
              </a>
            </div>
            <?php
        endwhile;
    else :
        echo '<p class="text-center text-muted">Žádná fotoalba nebyla nalezena.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<?php get_footer(); ?>
