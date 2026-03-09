<?php
/**
 * Detail týmu CPT (single-tym.php)
 * Zobrazuje informace o týmu a soupisku hráčů
 */
get_header();

while (have_posts()) : the_post();
    $pocet_hracu   = get_post_meta(get_the_ID(), 'pocet_hracu', true);
    $hlavni_trener = get_post_meta(get_the_ID(), 'hlavni_trener', true);
    $asistent      = get_post_meta(get_the_ID(), 'asistent_trenera', true);
    $zdravotnik    = get_post_meta(get_the_ID(), 'zdravotnik', true);
    $tym_slug      = get_post_meta(get_the_ID(), 'tym_slug', true);

    $kat_terms  = get_the_terms(get_the_ID(), 'kategorie-tymu');
    $sez_terms  = get_the_terms(get_the_ID(), 'sezona');
    $kat_nazev  = (!is_wp_error($kat_terms) && $kat_terms) ? $kat_terms[0]->name : '';
    $sez_nazev  = (!is_wp_error($sez_terms) && $sez_terms) ? $sez_terms[0]->name : '';
?>

<div class="container py-5">

  <!-- Navigace zpět -->
  <div class="mb-4">
    <a href="<?php echo esc_url(get_post_type_archive_link('tym')); ?>" class="btn btn-outline-secondary btn-sm">
      &larr; Všechny týmy
    </a>
  </div>

  <div class="row">
    <div class="col-md-8">
      <h2 class="fw-bold mb-1"><?php the_title(); ?></h2>
      <?php if ($kat_nazev || $sez_nazev) : ?>
        <p class="text-muted mb-4">
          <?php echo esc_html($kat_nazev); ?>
          <?php if ($kat_nazev && $sez_nazev) echo ' &bull; '; ?>
          <?php echo esc_html($sez_nazev); ?>
        </p>
      <?php endif; ?>

      <!-- INFO BOX TRENÉŘI -->
      <div class="p-3 border rounded-3 mb-4">
        <div class="row g-3">
          <?php if ($pocet_hracu) : ?>
            <div class="col-md-3">
              <strong>Počet hráčů:</strong><br>
              <?php echo esc_html($pocet_hracu); ?>
            </div>
          <?php endif; ?>
          <?php if ($hlavni_trener) : ?>
            <div class="col-md-3">
              <strong>Hlavní trenér:</strong><br>
              <?php echo esc_html($hlavni_trener); ?>
            </div>
          <?php endif; ?>
          <?php if ($asistent) : ?>
            <div class="col-md-3">
              <strong>Asistent trenéra:</strong><br>
              <?php echo esc_html($asistent); ?>
            </div>
          <?php endif; ?>
          <?php if ($zdravotnik) : ?>
            <div class="col-md-3">
              <strong>Zdravotník:</strong><br>
              <?php echo esc_html($zdravotnik); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Popis týmu -->
      <?php
      $content = get_the_content();
      if ($content) :
      ?>
        <div class="mb-4">
          <?php the_content(); ?>
        </div>
      <?php endif; ?>

    </div>

    <div class="col-md-4 text-center">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded club-logo')); ?>
      <?php else : ?>
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
             alt="TJ Slavoj Mýto" class="img-fluid club-logo mt-4">
      <?php endif; ?>
    </div>
  </div>

  <!-- SOUPISKA HRÁČŮ -->
  <?php if ($tym_slug) : ?>
    <div class="row mt-2">
      <div class="col-md-12">
        <h5 class="fw-bold mb-3">Soupiska hráčů</h5>
        <div class="p-3 border rounded-3">
          <?php slavoj_vypis_hrace_tymu($tym_slug); ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- PŘÍŠTÍ ZÁPAS -->
  <div class="row mt-4">
    <div class="col-md-12">
      <h5 class="fw-bold mb-3">Příští zápas</h5>
      <?php
      $zapasy_args = array(
          'post_type'      => 'zapas',
          'posts_per_page' => 1,
          'meta_key'       => 'datum_zapasu',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
          'tax_query'      => array(
              'relation' => 'AND',
              array('taxonomy' => 'stav-zapasu', 'field' => 'slug', 'terms' => 'nadchazejici'),
          ),
      );

      if ($kat_terms && !is_wp_error($kat_terms)) {
          $zapasy_args['tax_query'][] = array(
              'taxonomy' => 'kategorie-tymu',
              'field'    => 'term_id',
              'terms'    => wp_list_pluck($kat_terms, 'term_id'),
          );
      }

      $zapasy_q = new WP_Query($zapasy_args);
      if ($zapasy_q->have_posts()) :
          $zapasy_q->the_post();
          $datum  = get_post_meta(get_the_ID(), 'datum_zapasu', true);
          $cas    = get_post_meta(get_the_ID(), 'cas_zapasu',   true);
          $domaci = get_post_meta(get_the_ID(), 'domaci',       true);
          $hoste  = get_post_meta(get_the_ID(), 'hoste',        true);
          $datum_fmt = $datum ? date_i18n('j. n. Y', strtotime($datum)) : '';
          $slavoj_domaci = slavoj_is_club_team($domaci);
          $home_cls = 'match-card__team match-card__team--home' . ($slavoj_domaci ? ' match-card__team--slavoj' : '');
          $away_cls = 'match-card__team match-card__team--away' . (!$slavoj_domaci && slavoj_is_club_team($hoste) ? ' match-card__team--slavoj' : '');
          wp_reset_postdata();
          get_template_part('template-parts/card', 'match', array(
              'datum'      => $datum,
              'datum_fmt'  => $datum_fmt,
              'cas'        => $cas,
              'domaci'     => $domaci,
              'hoste'      => $hoste,
              'skore'      => '',
              'strelci'    => '',
              'card_cls'   => 'match-card--upcoming',
              'score_cls'  => 'match-card__score match-card__score--upcoming',
              'badge_cls'  => 'badge--primary',
              'badge_text' => 'Nadcházející',
              'home_cls'   => $home_cls,
              'away_cls'   => $away_cls,
          ));
      else :
          echo '<p class="text-muted small">Žádný příští zápas.</p>';
      endif;
      wp_reset_postdata();
      ?>
    </div>
  </div>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>
