<?php
/**
 * header.php — Hlavička každé stránky webu.
 *
 * WordPress automaticky vloží obsah tohoto souboru pokaždé,
 * když nějaká šablona zavolá get_header().
 *
 * Obsahuje: <!doctype>, <html>, <head> (meta, styly, skripty)
 * a otevírací <body> + <main>.
 *
 * Uzavírací tagy jsou ve footer.php (voláno přes get_footer()).
 */
?>
<!doctype html>
<!-- ↑ Říká prohlížeči, že jde o HTML5 dokument -->

<html <?php language_attributes(); ?>>
<!-- ↑ language_attributes() vypíše lang="cs-CZ" (jazyk webu nastavený v WP) -->

<head>
  <!-- Znaková sada – UTF-8 podporuje českou diakritiku -->
  <meta charset="<?php bloginfo('charset'); ?>">

  <!-- Viewport – nutný pro responzivní (mobilní) zobrazení.
       width=device-width = šířka stránky = šířka obrazovky zařízení
       initial-scale=1    = žádné výchozí přiblížení/oddálení -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php wp_head(); ?>
  <!-- ↑ KLÍČOVÁ FUNKCE: wp_head() vypíše do <head> vše, co WordPress
       a pluginy potřebují — všechny CSS styly (registrované přes
       wp_enqueue_style), skripty, meta tagy, favicon atd.
       BEZ TOHOTO by se nenačetl Bootstrap ani naše vlastní CSS! -->
</head>

<body <?php body_class(); ?>>
<!-- ↑ body_class() přidá na <body> CSS třídy podle aktuální stránky,
     např. "home page-template-front-page logged-in".
     Díky tomu můžeme v CSS cílit styly na konkrétní typy stránek. -->

<?php wp_body_open(); ?>
<!-- ↑ Háček (hook) hned za <body>. Pluginy sem mohou vložit kód
     (např. Google Analytics tracking script). -->

<?php get_template_part('template-parts/site', 'header'); ?>
<!-- ↑ Načte soubor template-parts/site-header.php
     (spojí "site" + "-" + "header" → site-header.php).
     Obsahuje navigační lištu (navbar) webu. -->

<main id="content" class="site-main">
<!-- ↑ Otevíráme hlavní obsahovou oblast stránky.
     Každá šablona (front-page.php, page-zapasy.php atd.)
     vloží svůj obsah SEM, mezi <main> a </main>.
     Uzavírací </main> je ve footer.php. -->
