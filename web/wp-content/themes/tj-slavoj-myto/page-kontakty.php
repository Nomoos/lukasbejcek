<?php
/**
 * page-kontakty.php — STRÁNKA KONTAKTŮ
 * =======================================
 * Zobrazuje kontaktní karty členů výboru klubu + mapu.
 * Šablona pro stránku se slugem "kontakty".
 *
 * DATA: Kontakty jsou CPT 'kontakt', každý má meta pole:
 *   pozice  = funkce v klubu (předseda, pokladník...)
 *   telefon = telefonní číslo
 *   email   = e-mailová adresa
 *   poradi  = číslo pro řazení (1 = zobrazí se první)
 */

get_header();
?>

<div class="container py-5 text-center">
  <h2 class="fw-bold mb-5"><?php echo esc_html(get_the_title(get_queried_object_id())); ?></h2>

  <!-- justify-content-center = Bootstrap: centruje sloupce, pokud jich je méně než 12 -->
  <div class="row g-4 justify-content-center">
    <?php
    /**
     * WP_Query pro kontakty — načte VŠECHNY (-1), seřazené podle meta pole 'poradi'.
     *
     * 'orderby' => 'meta_value_num' — řadí podle ČÍSELNÉ hodnoty meta pole
     *   (meta_value = textové řazení, meta_value_num = číselné: 1, 2, 10, ne 1, 10, 2)
     * 'meta_key' => 'poradi' — podle kterého meta pole řadit
     */
    $args = array(
        'post_type'      => 'kontakt',
        'posts_per_page' => -1,          // -1 = zobrazit všechny (bez limitu)
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'poradi',
        'order'          => 'ASC',       // Vzestupně (pořadí 1 → 2 → 3...)
    );
    $kontakty_query = new WP_Query($args);

    if ($kontakty_query->have_posts()) :
        while ($kontakty_query->have_posts()) :
            $kontakty_query->the_post();

            // Načtení meta polí kontaktu
            $pozice = esc_html(get_post_meta(get_the_ID(), 'pozice', true));
            $telefon = esc_html(get_post_meta(get_the_ID(), 'telefon', true));
            $email = sanitize_email(get_post_meta(get_the_ID(), 'email', true));
            ?>
            <!-- col-md-4 = 3 sloupce na md, col-lg-3 = 4 sloupce na lg -->
            <div class="col-md-4 col-lg-3">
              <div class="committee-card p-4">

                <!-- FOTOGRAFIE / PLACEHOLDER — logo jako fallback -->
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('thumbnail', array(
                      'class' => 'committee-photo',
                  )); ?>
                <?php else : ?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png" class="committee-photo" alt="TJ Slavoj Mýto">
                <?php endif; ?>

                <h4 class="mb-1"><?php the_title(); ?></h4>

                <?php if ($pozice) : ?>
                  <p class="text-muted small mb-3"><?php echo $pozice; ?></p>
                <?php endif; ?>

                <?php if ($telefon) : ?>
                  <p class="small mb-0">
                    <strong>Tel.:</strong>
                    <!-- href="tel:..." = mobilní telefon otevře vytáčení
                         preg_replace('/\s+/', '', ...) = odstraní mezery z čísla
                         (tel: protokol nesmí obsahovat mezery) -->
                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $telefon)); ?>"><?php echo $telefon; ?></a>
                  </p>
                <?php endif; ?>

                <?php if ($email) : ?>
                  <p class="small mb-0">
                    <strong>Email:</strong>
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                  </p>
                <?php endif; ?>
              </div>
            </div>
            <?php
        endwhile;
    else :
        echo '<p class="text-muted">Zatím nejsou k dispozici žádné kontakty.</p>';
    endif;
    wp_reset_postdata();
    ?>
  </div>

  <!-- MAPA — OpenStreetMap embed (iframe) -->
  <div class="map-container">
    <!-- get_theme_mod() = hodnota z Customizeru, TJSM_DEFAULT_MAP_URL = fallback konstanta
         loading="lazy" = obrázek/iframe se načte až když uživatel scrollne k němu (výkon)
         title = popis pro čtečky obrazovek (přístupnost) -->
    <iframe
      src="<?php echo esc_url(get_theme_mod('tjsm_mapa_url', TJSM_DEFAULT_MAP_URL)); ?>"
      allowfullscreen
      loading="lazy"
      title="Mapa – TJ Slavoj Mýto"
    ></iframe>
  </div>
</div>

<?php get_footer(); ?>
