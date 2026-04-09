<?php
/**
 * single.php — DETAIL JEDNOHO PŘÍSPĚVKU (obecný)
 * =================================================
 * WordPress použije tuto šablonu pro zobrazení detailu příspěvku (post).
 *
 * Stejně jako u page.php, specifičtější šablony mají přednost:
 *   single-zapas.php   → detail zápasu
 *   single-tym.php     → detail týmu
 *   single-hrac.php    → detail hráče
 *   single-galerie.php → detail galerie
 *   single.php         → vše ostatní (tento soubor)
 *
 * Rozdíl page.php vs single.php:
 *   page.php   = statické stránky (O nás, Kontakty)
 *   single.php = dynamické příspěvky s datem (aktuality, novinky)
 */

get_header(); // Načte header.php
?>

<div class="container py-5">
  <?php while (have_posts()) : the_post(); ?>

    <!-- <article> = sémantický HTML5 tag pro samostatný článek/příspěvek -->
    <article>
      <h1 class="mb-2"><?php the_title(); // Nadpis příspěvku ?></h1>

      <!-- Datum publikace příspěvku -->
      <p class="text-muted mb-4">
        <small><?php echo esc_html(get_the_date('j. n. Y')); ?></small>
        <!-- get_the_date() VRACÍ řetězec (neechuje), proto musíme ručně echo + esc_html() -->
        <!-- 'j. n. Y' = český formát data: den. měsíc. rok (např. "9. 4. 2026") -->
      </p>

      <?php if (has_post_thumbnail()) : ?>
      <!-- ↑ has_post_thumbnail() = má příspěvek nastavený "Náhledový obrázek"?
           (v editoru: pravý panel → Náhledový obrázek / Featured Image) -->
        <div class="mb-4">
          <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
          <!-- ↑ Vypíše <img> tag náhledového obrázku.
               'large' = velikost obrázku (WP generuje více velikostí při uploadu)
               'img-fluid' = Bootstrap: obrázek se přizpůsobí šířce kontejneru
               'rounded'   = Bootstrap: zaoblené rohy -->
        </div>
      <?php endif; ?>

      <div class="entry-content">
        <?php the_content(); // Celý obsah příspěvku z editoru ?>
      </div>

      <!-- Tlačítko "Zpět" — odkaz na archiv daného post type -->
      <div class="mt-5">
        <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type())); ?>" class="btn btn-outline-secondary">
          <!-- get_post_type()              = zjistí typ aktuálního příspěvku (post, zapas...)
               get_post_type_archive_link() = vrátí URL archivu pro daný typ
               Např. pro "post" → /blog/, pro "zapas" → /zapasy/ -->
          &larr; Zpět
        </a>
      </div>
    </article>
  <?php endwhile; ?>
</div>

<?php get_footer(); // Načte footer.php ?>
