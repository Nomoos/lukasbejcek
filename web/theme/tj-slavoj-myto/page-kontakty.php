<?php
/**
 * Template Name: Kontakty
 * Stránka s kontakty na členy výboru TJ Slavoj Mýto
 * Obsahuje: kontaktní karty (foto/placeholder, jméno, pozice, telefon, email), mapa
 */
get_header();
?>

<div class="container py-5 text-center">
  <h2 class="fw-bold mb-5">Výbor klubu TJ Slavoj Mýto</h2>

  <div class="row g-4 justify-content-center">
    <?php
    $args = array(
        'category_name'  => 'kontakty',
        'posts_per_page' => -1,
    );
    $kontakty_query = new WP_Query($args);

    if ($kontakty_query->have_posts()) :
        while ($kontakty_query->have_posts()) :
            $kontakty_query->the_post();
            $pozice = esc_html(get_post_meta(get_the_ID(), 'pozice', true));
            $telefon = esc_html(get_post_meta(get_the_ID(), 'telefon', true));
            $email = sanitize_email(get_post_meta(get_the_ID(), 'email', true));
            ?>
            <div class="col-md-4 col-lg-3">
              <div class="committee-card p-4">
                <!-- FOTOGRAFIE / PLACEHOLDER -->
                <div class="committee-img-wrapper">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('thumbnail', array(
                        'class' => 'committee-img',
                        'style' => 'border-radius: 50%; width: 120px; height: 120px; object-fit: cover;',
                    )); ?>
                  <?php else : ?>
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" class="committee-img" alt="TJ Slavoj Mýto">
                  <?php endif; ?>
                </div>

                <!-- JMÉNO -->
                <h4 class="mb-1"><?php the_title(); ?></h4>

                <!-- POZICE -->
                <?php if ($pozice) : ?>
                  <p class="text-muted mb-3" style="font-size: 14px;"><?php echo $pozice; ?></p>
                <?php endif; ?>

                <!-- TELEFON -->
                <?php if ($telefon) : ?>
                  <p class="mb-0" style="font-size: 14px;">
                    <strong>Tel.:</strong>
                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $telefon)); ?>"><?php echo $telefon; ?></a>
                  </p>
                <?php endif; ?>

                <!-- EMAIL -->
                <?php if ($email) : ?>
                  <p class="mb-0" style="font-size: 14px;">
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

  <!-- MAPA -->
  <div class="map-container">
    <iframe
      src="https://frame.mapy.cz/s/gusoheruvo"
      allowfullscreen
      loading="lazy"
      title="Mapa – TJ Slavoj Mýto"
    ></iframe>
  </div>
</div>

<?php get_footer(); ?>
