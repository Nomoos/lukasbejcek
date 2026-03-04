<?php
/**
 * Template Name: Galerie
 * Stránka s fotoalbami TJ Slavoj Mýto
 * Obsahuje: filtry (tým, sezóna), mřížka fotoalb s náhledem a názvem
 */
get_header();

// Získání filtrů z GET parametrů
$filtr_kategorie = isset($_GET['kategorie']) ? sanitize_text_field(wp_unslash($_GET['kategorie'])) : '';
$filtr_sezona = isset($_GET['sezona']) ? sanitize_text_field(wp_unslash($_GET['sezona'])) : '';

$kategorie_tymu_terms = get_terms(array(
    'taxonomy'   => 'kategorie-tymu',
    'hide_empty' => false,
));

$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'DESC',
));
?>

<div class="container py-5">
    <h2 class="mb-0"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h2>
    <p class="text-muted mb-4"><?php
        $sub = get_the_excerpt(get_queried_object_id());
        echo $sub ? esc_html(wp_strip_all_tags($sub)) : esc_html(sprintf('Fotografie z klubového života %s', get_bloginfo('name')));
    ?></p>

    <!-- FILTRY – selecty odešlou formulář ihned po změně; tlačítko jako záloha bez JS -->
    <form method="get" class="d-flex gap-3 mb-4 flex-wrap">
      <label class="sr-only" for="f-kategorie">Kategorie</label>
      <select id="f-kategorie" name="kategorie" class="form-select bg-light filter-select-team-sm" onchange="this.form.submit()">
        <option value="">Všechny kategorie</option>
        <?php if (!empty($kategorie_tymu_terms) && !is_wp_error($kategorie_tymu_terms)) : ?>
          <?php foreach ($kategorie_tymu_terms as $term) : ?>
            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($filtr_kategorie, $term->slug); ?>>
              <?php echo esc_html($term->name); ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
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

      <noscript>
        <button type="submit" class="btn btn-primary">Filtrovat</button>
      </noscript>
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

    if ($filtr_kategorie) {
        $tax_query[] = array(
            'taxonomy' => 'kategorie-tymu',
            'field'    => 'slug',
            'terms'    => $filtr_kategorie,
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
