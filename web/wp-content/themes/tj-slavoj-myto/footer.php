<?php
/**
 * footer.php — Patička každé stránky webu.
 *
 * WordPress automaticky vloží obsah tohoto souboru pokaždé,
 * když nějaká šablona zavolá get_footer().
 *
 * Uzavírá <main> (otevřený v header.php), načte patičku webu
 * a uzavře <body> a <html>.
 */
?>

</main><!-- /#content — uzavírá <main> otevřený v header.php -->

<?php get_template_part('template-parts/site', 'footer'); ?>
<!-- ↑ Načte template-parts/site-footer.php — patička s logem,
     kontaktem a copyright textem -->

<?php wp_footer(); ?>
<!-- ↑ KLÍČOVÁ FUNKCE: wp_footer() vypíše skripty na konec stránky.
     Sem WordPress umístí všechny JS soubory registrované
     přes wp_enqueue_script() s parametrem $in_footer = true
     (např. bootstrap.bundle.min.js).
     Skripty na konci stránky = stránka se vykreslí dříve. -->

</body>
</html>
