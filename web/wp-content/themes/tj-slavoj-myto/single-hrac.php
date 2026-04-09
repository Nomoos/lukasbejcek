<?php
/**
 * single-hrac.php — DETAIL JEDNOHO HRÁČE
 * =========================================
 * WordPress použije na URL: /hrac/jmeno-hrace/
 *
 * Zobrazuje: fotku, číslo dresu, rok narození, pozici, odkaz na tým.
 *
 * ZAJÍMAVOST: Odkaz na tým se zjišťuje přes WP_Query —
 * hledáme CPT 'tym', jehož meta pole 'tym_slug' odpovídá
 * meta poli 'tym_slug' tohoto hráče. Není to přímý odkaz (post ID),
 * ale textové propojení přes sdílený slug.
 */

get_header();

while (have_posts()) : the_post();

    // Meta pole hráče
    $cislo        = get_post_meta(get_the_ID(), 'cislo', true);        // Číslo dresu
    $rok_narozeni = get_post_meta(get_the_ID(), 'rok_narozeni', true); // Rok narození
    $tym_slug     = get_post_meta(get_the_ID(), 'tym_slug', true);     // Slug týmu (propojení)

    // Taxonomie přiřazené hráči
    $pozice_terms = get_the_terms(get_the_ID(), 'pozice-hrace');   // Brankář / Obránce / ...
    $kat_terms    = get_the_terms(get_the_ID(), 'kategorie-tymu'); // Muži A / Dorost / ...

    // Bezpečné čtení názvu termu
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
              /**
               * PROPOJENÍ HRÁČ → TÝM přes meta pole.
               *
               * WP_Query hledá CPT 'tym', kde meta_key 'tym_slug'
               * má stejnou hodnotu jako tym_slug tohoto hráče.
               *
               * Pokud najde tým → zobrazí odkaz na jeho detail.
               * Pokud ne → zobrazí jen textový slug.
               *
               * POZN: 'meta_key' + 'meta_value' je starší syntaxe WP_Query.
               * Novější je meta_query s polem. Obojí funguje.
               */
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
                  echo esc_html($tym_slug); // Fallback: jen text
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
