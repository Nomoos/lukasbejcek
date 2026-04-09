<?php
/**
 * page.php — OBECNÁ ŠABLONA PRO STRÁNKY
 * =======================================
 * WordPress použije tento soubor pro zobrazení jakékoli "Stránky"
 * (Pages — statický obsah, ne příspěvky/posty).
 *
 * ALE: pokud existuje specifičtější šablona, WP ji použije přednostně:
 *   page-zapasy.php   → pro stránku se slugem "zapasy"
 *   page-kontakty.php → pro stránku se slugem "kontakty"
 *   page.php          → pro všechny ostatní stránky (tento soubor)
 *
 * V našem webu máme specifické page-*.php pro většinu stránek,
 * takže page.php se použije jen pro stránky bez vlastní šablony
 * (např. stránka "O nás" vytvořená v administraci).
 */

get_header(); // Načte header.php
?>

<div class="container py-5">
  <?php while (have_posts()) : the_post(); ?>
  <!-- ↑ WordPress Loop — i pro jedinou stránku musíme použít smyčku,
       protože the_post() nastaví globální proměnné ($post),
       bez nichž by the_title() a the_content() nefungovaly. -->

    <h1 class="mb-4"><?php the_title(); // Nadpis stránky ?></h1>

    <div class="entry-content">
      <?php the_content(); ?>
      <!-- ↑ the_content() = vypíše celý obsah stránky z WYSIWYG editoru
           (blokový editor Gutenberg nebo klasický editor).
           Na rozdíl od the_excerpt() zobrazí KOMPLETNÍ obsah. -->
    </div>
  <?php endwhile; ?>
</div>

<?php get_footer(); // Načte footer.php ?>
