<?php
/**
 * page-sponzori.php — STRÁNKA SPONZORŮ
 * =======================================
 * Zobrazuje mřížku sponzorských karet — logo, název, odkaz na web.
 * Šablona pro stránku se slugem "sponzori".
 *
 * DATA: Sponzoři jsou CPT 'sponzor' s meta polem 'web_sponzora' (URL webu).
 * Pokud sponzor má web → karta je klikací odkaz. Pokud ne → jen div.
 */

get_header();
?>

<div class="container py-5 text-center">
  <h2 class="fw-bold mb-5"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h2>

  <div class="row g-4 justify-content-center">
    <?php
    // Načte všechny sponzory bez omezení počtu
    $args = array(
        'post_type'      => 'sponzor',
        'posts_per_page' => -1,
    );
    $sponzori_query = new WP_Query($args);

    if ($sponzori_query->have_posts()) :
        while ($sponzori_query->have_posts()) :
            $sponzori_query->the_post();

            // Načtení URL webu sponzora z meta pole
            $web_sponzora = get_post_meta(get_the_ID(), 'web_sponzora', true);
            $has_link = !empty($web_sponzora); // Má sponzor web?
            ?>
            <div class="col-md-4 col-lg-3">

              <?php if ($has_link) : ?>
                <!-- Pokud má web → celá karta je odkaz (<a>)
                     target="_blank"        = otevře v novém tabu
                     rel="noopener noreferrer" = bezpečnostní atributy:
                       noopener  = nová stránka nemá přístup k window.opener
                       noreferrer = neposílá Referer hlavičku (soukromí)
                     d-block = Bootstrap: display:block (aby <a> fungoval jako blok) -->
                <a href="<?php echo esc_url($web_sponzora); ?>" target="_blank" rel="noopener noreferrer" class="sponsor-card d-block">
              <?php else : ?>
                <!-- Pokud nemá web → karta je obyčejný <div> -->
                <div class="sponsor-card">
              <?php endif; ?>

                <div class="committee-img-wrapper">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium', array(
                        'class' => 'committee-img',
                        'alt'   => get_the_title(),
                    )); ?>
                  <?php else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png" class="committee-img" alt="<?php the_title(); ?>">
                  <?php endif; ?>
                </div>
                <h5 class="mb-1"><?php the_title(); ?></h5>

              <!-- Uzavíráme buď </a> nebo </div> podle toho, zda sponzor má web -->
              <?php if ($has_link) : ?>
                </a>
              <?php else : ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
        endwhile;
    else :
        echo '<p class="text-muted">Zatím nejsou k dispozici žádní sponzoři.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>
</div>

<?php get_footer(); ?>
