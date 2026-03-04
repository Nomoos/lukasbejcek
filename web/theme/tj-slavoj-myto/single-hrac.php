<?php
/**
 * Detail hráče CPT (single-hrac.php)
 * Zobrazuje profil hráče: fotografie, číslo dresu, rok narození, pozice, tým
 */
get_header();

while (have_posts()) : the_post();
    $cislo        = get_post_meta(get_the_ID(), 'cislo', true);
    $rok_narozeni = get_post_meta(get_the_ID(), 'rok_narozeni', true);
    $tym_slug     = get_post_meta(get_the_ID(), 'tym_slug', true);

    $pozice_terms = get_the_terms(get_the_ID(), 'pozice-hrace');
    $kat_terms    = get_the_terms(get_the_ID(), 'kategorie-tymu');

    $pozice_nazev = (!is_wp_error($pozice_terms) && $pozice_terms) ? $pozice_terms[0]->name : '';
    $kat_nazev    = (!is_wp_error($kat_terms) && $kat_terms) ? $kat_terms[0]->name : '';
?>

<div class="container py-5">

  <!-- Navigace zpět -->
  <div class="mb-4">
    <a href="<?php echo esc_url(get_post_type_archive_link('hrac')); ?>" class="btn btn-outline-secondary btn-sm">
      &larr; Všichni hráči
    </a>
  </div>

  <div class="row align-items-start">

    <!-- Fotografie -->
    <div class="col-md-3 text-center mb-4">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded club-logo')); ?>
      <?php else : ?>
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
             alt="TJ Slavoj Mýto" class="img-fluid club-logo">
      <?php endif; ?>
    </div>

    <!-- Informace -->
    <div class="col-md-9">
      <h2 class="fw-bold mb-1"><?php the_title(); ?></h2>

      <?php if ($pozice_nazev || $kat_nazev) : ?>
        <p class="text-muted mb-4">
          <?php echo esc_html($pozice_nazev); ?>
          <?php if ($pozice_nazev && $kat_nazev) echo ' &bull; '; ?>
          <?php echo esc_html($kat_nazev); ?>
        </p>
      <?php endif; ?>

      <div class="p-3 border rounded-3 mb-4">
        <div class="row g-3">
          <?php if ($cislo) : ?>
            <div class="col-md-4">
              <strong>Číslo dresu:</strong><br>
              <?php echo esc_html($cislo); ?>
            </div>
          <?php endif; ?>
          <?php if ($rok_narozeni) : ?>
            <div class="col-md-4">
              <strong>Rok narození:</strong><br>
              <?php echo esc_html($rok_narozeni); ?>
            </div>
          <?php endif; ?>
          <?php if ($tym_slug) : ?>
            <div class="col-md-4">
              <strong>Tým:</strong><br>
              <?php
              $tym_q = new WP_Query(array(
                  'post_type'      => 'tym',
                  'posts_per_page' => 1,
                  'meta_key'       => 'tym_slug',
                  'meta_value'     => $tym_slug,
              ));
              if ($tym_q->have_posts()) {
                  $tym_q->the_post();
                  echo '<a href="' . esc_url(get_permalink()) . '">'
                       . esc_html(get_the_title()) . '</a>';
                  wp_reset_postdata();
              } else {
                  echo esc_html($tym_slug);
              }
              ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <?php
      $content = get_the_content();
      if ($content) :
      ?>
        <div class="mb-4">
          <?php the_content(); ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>
