<?php
/**
 * Detail zápasu CPT (single-zapas.php)
 * Zobrazuje detailní informace o jednom zápase
 */
get_header();

while (have_posts()) : the_post();
    $datum   = get_post_meta(get_the_ID(), 'datum_zapasu', true);
    $cas     = get_post_meta(get_the_ID(), 'cas_zapasu', true);
    $domaci  = get_post_meta(get_the_ID(), 'domaci', true);
    $hoste   = get_post_meta(get_the_ID(), 'hoste', true);
    $skore   = get_post_meta(get_the_ID(), 'skore', true);
    $strelci = get_post_meta(get_the_ID(), 'strelci', true);
    $misto   = get_post_meta(get_the_ID(), 'misto_konani', true);

    // Kategorie týmu a sezóna z taxonomií
    $kategorie_tymu = get_the_terms(get_the_ID(), 'kategorie-tymu');
    $sezony         = get_the_terms(get_the_ID(), 'sezona');
    $stav_terms     = get_the_terms(get_the_ID(), 'stav-zapasu');

    $nazev_tymu  = (!is_wp_error($kategorie_tymu) && $kategorie_tymu) ? $kategorie_tymu[0]->name : '';
    $nazev_sez   = (!is_wp_error($sezony) && $sezony) ? $sezony[0]->name : '';
    $stav_zapasu = (!is_wp_error($stav_terms) && $stav_terms) ? $stav_terms[0]->name : ($skore ? 'Odehraný' : 'Nadcházející');

    // Formátování data
    $datum_format = '';
    if ($datum) {
        $dt = DateTime::createFromFormat('Y-m-d', $datum);
        $datum_format = $dt ? $dt->format('j. n. Y') : $datum;
    }
?>

<div class="container py-5">

  <!-- Navigace zpět -->
  <div class="mb-4">
    <a href="<?php echo esc_url(get_post_type_archive_link('zapas')); ?>" class="btn btn-outline-secondary btn-sm">
      &larr; Všechny zápasy
    </a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">

      <!-- Hlavička zápasu -->
      <div class="card shadow-sm mb-4">
        <div class="card-body text-center py-5">

          <?php if ($nazev_tymu || $nazev_sez) : ?>
            <p class="text-muted mb-3">
              <?php echo esc_html($nazev_tymu); ?>
              <?php if ($nazev_tymu && $nazev_sez) echo ' &bull; '; ?>
              <?php echo esc_html($nazev_sez); ?>
            </p>
          <?php endif; ?>

          <div class="d-flex justify-content-center align-items-center gap-4 mb-3">
            <div class="text-center">
              <h3 class="fw-bold mb-0"><?php echo esc_html($domaci ?: '—'); ?></h3>
              <small class="text-muted">Domácí</small>
            </div>

            <div class="text-center px-4">
              <span class="display-4 fw-bold text-primary">
                <?php echo $skore ? esc_html($skore) : 'vs'; ?>
              </span>
              <?php if ($datum_format || $cas) : ?>
                <p class="text-muted mb-0 mt-1 small">
                  <?php echo esc_html($datum_format); ?>
                  <?php echo $cas ? ' v ' . esc_html($cas) : ''; ?>
                </p>
              <?php endif; ?>
            </div>

            <div class="text-center">
              <h3 class="fw-bold mb-0"><?php echo esc_html($hoste ?: '—'); ?></h3>
              <small class="text-muted">Hosté</small>
            </div>
          </div>

          <!-- Badge stav zápasu -->
          <span class="badge <?php echo $skore ? 'bg-success' : 'bg-secondary'; ?>">
            <?php echo esc_html($stav_zapasu); ?>
          </span>
        </div>
      </div>

      <!-- Detaily -->
      <div class="row g-3 mb-4">
        <?php if ($datum_format) : ?>
          <div class="col-md-4">
            <div class="border rounded p-3 text-center">
              <div class="text-muted small">Datum</div>
              <strong><?php echo esc_html($datum_format); ?></strong>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($cas) : ?>
          <div class="col-md-4">
            <div class="border rounded p-3 text-center">
              <div class="text-muted small">Čas výkopu</div>
              <strong><?php echo esc_html($cas); ?></strong>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($misto) : ?>
          <div class="col-md-4">
            <div class="border rounded p-3 text-center">
              <div class="text-muted small">Místo konání</div>
              <strong><?php echo esc_html($misto); ?></strong>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Střelci -->
      <?php if ($strelci) : ?>
        <div class="border rounded p-3 mb-4">
          <h5 class="fw-bold mb-2">Střelci</h5>
          <p class="mb-0"><?php echo esc_html($strelci); ?></p>
        </div>
      <?php endif; ?>

      <!-- Obsah příspěvku (poznámky, popis) -->
      <?php
      $content = get_the_content();
      if ($content) :
      ?>
        <div class="border rounded p-3 mb-4">
          <h5 class="fw-bold mb-2">Popis zápasu</h5>
          <?php the_content(); ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
