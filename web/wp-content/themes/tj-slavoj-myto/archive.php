<?php
/**
 * archive.php — OBECNÝ ARCHIV (VÝPIS PŘÍSPĚVKŮ)
 * ================================================
 * WordPress použije tuto šablonu pro zobrazení seznamu příspěvků
 * seskupených podle kategorie, štítku, taxonomie nebo data.
 *
 * Specifičtější archivní šablony mají přednost:
 *   archive-zapas.php   → výpis zápasů
 *   archive-tym.php     → výpis týmů
 *   archive-galerie.php → výpis galerií
 *   archive.php         → vše ostatní (tento soubor)
 *
 * ARCHIV vs SINGLE:
 *   archive = SEZNAM příspěvků (výpis karet, tabulka)
 *   single  = DETAIL jednoho příspěvku
 *
 * Příklady URL archivů:
 *   /category/novinky/    → kategorie "novinky"
 *   /tag/turnaj/          → štítek "turnaj"
 *   /sezona/2024-25/      → vlastní taxonomie "sezóna"
 *   /2026/04/             → příspěvky z dubna 2026
 */

get_header(); // Načte header.php
?>

<div class="container py-5">
  <h1 class="mb-4">
    <?php
    /**
     * Dynamický nadpis podle typu archivu:
     * WordPress poskytuje funkce is_*() pro zjištění typu stránky.
     *
     * is_category() = archiv WordPress kategorie (vestavěná taxonomie)
     * is_tag()      = archiv WordPress štítku (vestavěná taxonomie)
     * is_tax()      = archiv VLASTNÍ taxonomie (sezona, kategorie-tymu...)
     * is_date()     = archiv podle data (měsíc/rok)
     *
     * single_cat_title('', false) — vrátí název kategorie.
     *   '' = žádný prefix před názvem
     *   false = VRÁTÍ text (neechuje), proto musíme ručně echo
     */
    if (is_category()) {
        echo 'Kategorie: ' . esc_html(single_cat_title('', false));
    } elseif (is_tag()) {
        echo 'Štítek: ' . esc_html(single_tag_title('', false));
    } elseif (is_tax()) {
        // Vlastní taxonomie (sezona, kategorie-tymu, stav-zapasu, pozice-hrace)
        echo esc_html(single_term_title('', false));
    } elseif (is_date()) {
        // 'F Y' = plný název měsíce + rok, např. "duben 2026"
        echo esc_html(get_the_date('F Y'));
    } else {
        echo 'Archiv';
    }
    ?>
  </h1>

  <div class="row g-4">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
      <!-- WordPress Loop — prochází příspěvky jeden po druhém -->

        <!-- col-md-6 = na středních+ obrazovkách 2 sloupce (6/12 gridu) -->
        <div class="col-md-6">
          <!-- Bootstrap Card komponenta:
               h-100     = výška 100% (karty ve stejném řádku budou stejně vysoké)
               shadow-sm = lehký stín -->
          <div class="card h-100 shadow-sm">

            <?php if (has_post_thumbnail()) : ?>
              <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                <!-- card-img-top = Bootstrap: obrázek nahoře v kartě -->
              </a>
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                  <?php the_title(); ?>
                </a>
              </h5>

              <p class="card-text text-muted small"><?php echo esc_html(get_the_date('j. n. Y')); ?></p>
              <p class="card-text"><?php the_excerpt(); // Krátký úryvek ?></p>

              <!-- btn-sm = malé tlačítko, btn-outline-primary = obrysové v hlavní barvě -->
              <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">Číst více</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else : ?>
      <p class="text-muted">Žádné příspěvky nebyly nalezeny.</p>
    <?php endif; ?>
  </div>

  <!-- Stránkování archivu -->
  <div class="mt-5">
    <?php the_posts_pagination(array('mid_size' => 2)); ?>
  </div>
</div>

<?php get_footer(); // Načte footer.php ?>
