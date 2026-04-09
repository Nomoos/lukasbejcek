<?php
/**
 * index.php — ZÁLOŽNÍ (FALLBACK) ŠABLONA
 * ========================================
 * Toto je POVINNÝ soubor v každé WordPress šabloně.
 * Je to šablona "poslední záchrany" — WordPress ji použije tehdy,
 * když pro daný typ obsahu neexistuje specifičtější šablona.
 *
 * WORDPRESS TEMPLATE HIERARCHY (hierarchie šablon):
 * WordPress hledá šablony od nejspecifičtější po nejobecnější:
 *   1. front-page.php     (hlavní stránka)
 *   2. page-{slug}.php    (konkrétní stránka, např. page-zapasy.php)
 *   3. page.php           (obecná stránka)
 *   4. single-{cpt}.php   (detail CPT, např. single-zapas.php)
 *   5. single.php         (obecný detail)
 *   6. archive-{cpt}.php  (archiv CPT)
 *   7. archive.php        (obecný archiv)
 *   8. index.php          ← SEM spadne vše, co nemá vlastní šablonu
 *
 * V našem webu tento soubor zobrazuje blog/aktuality.
 */

get_header(); // Načte header.php (HTML hlavička + navigace)
?>

<!-- .container = Bootstrap kontejner, py-5 = padding nahoře a dole -->
<div class="container py-5">
  <h1 class="mb-4">
    <?php
    /**
     * is_home()        — jsme na stránce s příspěvky (blog)?
     * is_front_page()  — jsme na titulní stránce webu?
     *
     * V WP může být hlavní stránka ≠ stránka s příspěvky.
     * (Nastavení → Čtení → "Statická stránka" vs "Stránka s příspěvky")
     *
     * Pokud je blog na jiné stránce než hlavní:
     *   → zobrazí název té stránky (single_post_title)
     * Jinak:
     *   → zobrazí text "Blog" / Fronpage header
     */
    if ( is_home() && ! is_front_page() ) {
        single_post_title(); // Vypíše název stránky pro příspěvky
    } else {
        esc_html_e( 'Blog', 'tj-slavoj-myto' );
        /**
         * esc_html_e() — kombinace 3 kroků v jedné funkci:
         *
         * 1. PŘEKLAD:  __('Blog', 'tj-slavoj-myto')
         *    Hledá překlad řetězce 'Blog' v překladovém souboru
         *    pro text domain 'tj-slavoj-myto'.
         *    Text domain = identifikátor, pod kterým WP hledá .po/.mo soubory
         *    v languages/ složce. Pokud překlad neexistuje, použije originál.
         *
         * 2. ESCAPOVÁNÍ:  esc_html(...)
         *    Převede speciální HTML znaky (< > & ") na bezpečné entity.
         *    Chrání proti XSS — kdyby překladový soubor obsahoval škodlivý kód.
         *
         * 3. VÝPIS:  echo ...
         *    Vypíše výsledek přímo na stránku.
         *
         * RODINA PŘEKLADOVÝCH FUNKCÍ:
         *   __('text', 'domain')       → VRÁTÍ přeložený text (neechuje)
         *   _e('text', 'domain')       → ECHUJE přeložený text (bez escapování!)
         *   esc_html__('text', 'domain') → VRÁTÍ přeložený + escapovaný text
         *   esc_html_e('text', 'domain') → ECHUJE přeložený + escapovaný text ← TOTO POUŽÍVÁME
         *
         * Pravidlo: pro výpis do HTML vždy esc_html_e(), nikdy samotné _e().
         */
    }
    ?>
  </h1>

  <!-- .row = Bootstrap řádek, g-4 = gap (mezera) 1.5rem mezi sloupci -->
  <div class="row g-4">

    <?php if ( have_posts() ) : ?>
    <!-- ↑ have_posts() = existují příspěvky k zobrazení?
         Toto je začátek tzv. "WordPress Loop" (smyčka).
         Loop = mechanismus, kterým WP prochází příspěvky jeden po druhém. -->

      <?php while ( have_posts() ) : the_post(); ?>
      <!-- ↑ while = dokud jsou příspěvky, opakuj.
           the_post() = načte další příspěvek a nastaví globální proměnné
           ($post), takže funkce jako the_title() vědí, o kterém příspěvku jde. -->

        <!-- col-md-6 = na střední obrazovce a větší zabere půlku šířky (6/12) -->
        <div class="col-md-6">
          <div class="aktualita">
            <h4>
              <!-- the_permalink() = URL příspěvku (detail) -->
              <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                <?php the_title(); // Vypíše název příspěvku ?>
              </a>
            </h4>

            <!-- get_the_date('j. n. Y') = datum ve formátu "9. 4. 2026" -->
            <p class="text-muted small mb-2"><?php echo esc_html( get_the_date( 'j. n. Y' ) ); ?></p>

            <?php the_excerpt(); // Krátký úryvek obsahu příspěvku (automaticky ořezaný) ?>
          </div>
        </div>
      <?php endwhile; // Konec smyčky (Loop) ?>

      <!-- Stránkování (pokud je příspěvků více než nastavený limit) -->
      <div class="col-12 mt-2">
        <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
        <!-- mid_size => 2 = kolik čísel stránek zobrazit vlevo/vpravo od aktuální -->
      </div>

    <?php else : ?>
      <!-- Pokud neexistují žádné příspěvky -->
      <p class="text-muted">Žádné příspěvky nebyly nalezeny.</p>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); // Načte footer.php (patička + uzavírací HTML tagy) ?>

