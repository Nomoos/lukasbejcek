<?php
/**
 * front-page.php — HLAVNÍ (ÚVODNÍ) STRÁNKA WEBU
 * =================================================
 * WordPress tuto šablonu použije jako PRVNÍ v hierarchii,
 * pokud je v Nastavení → Čtení zvolena "Statická stránka".
 *
 * Hierarchie pro hlavní stránku:
 *   1. front-page.php  ← TOTO (nejvyšší priorita)
 *   2. home.php
 *   3. index.php
 *
 * STRUKTURA STRÁNKY:
 *   ┌─────────────────────────┐
 *   │  BANNER (foto + název)  │
 *   ├─────────────────────────┤
 *   │  KARTY NEJBLIŽŠÍCH      │
 *   │  ZÁPASŮ (4 kategorie)   │
 *   ├─────────────────────────┤
 *   │  DEKORATIVNÍ PRUHY      │
 *   ├─────────────────────────┤
 *   │  AKTUÁLNÍ ZPRÁVY        │
 *   └─────────────────────────┘
 *
 * NOVÉ KONCEPTY V TOMTO SOUBORU:
 * - WP_Query       — vlastní databázový dotaz na příspěvky
 * - get_post_meta  — čtení vlastních polí (meta dat) příspěvku
 * - tax_query      — filtrování příspěvků podle taxonomie
 * - meta_query     — filtrování příspěvků podle meta polí
 * - wp_reset_postdata — obnovení globálního $post po vlastním dotazu
 */

get_header(); // Načte header.php
?>

  <!-- ================================================================
       SEKCE 1: BANNER (úvodní obrázek s textem)
       ================================================================ -->
  <section class="banner">
    <?php
    /**
     * get_the_post_thumbnail_url() — vrátí URL náhledového obrázku stránky.
     *
     * get_queried_object_id() = ID aktuálně zobrazené stránky
     * 'full' = plná velikost obrázku (bez zmenšení)
     *
     * Pokud stránka nemá nastavený náhledový obrázek,
     * použijeme záložní obrázek ze složky šablony (img/banner.jpg).
     */
    $banner_url = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
    if ( ! $banner_url ) {
        $banner_url = get_template_directory_uri() . '/img/banner.jpg';
    }
    ?>
    <img src="<?php echo esc_url( $banner_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
    <div class="banner-text">
      <!-- fs-2 = Bootstrap font-size úroveň 2, fw-bold = font-weight bold -->
      <h1 class="fs-2 fw-bold"><?php bloginfo( 'name' ); ?></h1>
      <!-- bloginfo('description') = popis webu z Nastavení → Obecné -->
      <h5><?php bloginfo( 'description' ); ?></h5>
    </div>
  </section>

  <!-- ================================================================
       SEKCE 2: KARTY NEJBLIŽŠÍCH ZÁPASŮ
       ================================================================
       Pro každou ze 4 kategorií (Muži A, B, Dorost, Žáci) najdeme
       nejbližší nadcházející zápas a zobrazíme ho jako kartu.

       <section> = sémantický HTML5 tag pro tematickou sekci stránky
       aria-label = pojmenování sekce pro čtečky obrazovek (přístupnost)
       → Nevidomý uživatel uslyší: "Oblast: Nejbližší zápasy"
  -->
  <section class="section" aria-label="Nejbližší zápasy">
    <div class="container">
      <div class="zapasy-container">
        <?php
        /**
         * Pole kategorií, které chceme zobrazit na homepage.
         * Klíč = slug (URL-safe identifikátor) kategorie v taxonomii 'kategorie-tymu'
         * Hodnota = český název pro zobrazení na kartě
         *
         * Pořadí v poli = pořadí karet na stránce (Muži A → B → Dorost → Žáci).
         */
        $kategorie_poradi = array(
            'muzi-a'      => 'Muži A',
            'muzi-b'      => 'Muži B',
            'dorost'      => 'Dorost',
            'starsi-zaci' => 'Starší žáci',
        );

        /**
         * foreach — projde pole kategorií jednu po druhé.
         * $slug  = klíč (např. 'muzi-a')
         * $nazev = hodnota (např. 'Muži A')
         *
         * Pro KAŽDOU kategorii vytvoříme vlastní WP_Query dotaz.
         */
        foreach ( $kategorie_poradi as $slug => $nazev ) :

            /**
             * WP_Query — VLASTNÍ DATABÁZOVÝ DOTAZ
             * =====================================
             * Normální WordPress Loop (have_posts/the_post) pracuje s "hlavním"
             * dotazem, který WP automaticky vytvoří podle URL.
             *
             * Ale my potřebujeme VLASTNÍ dotaz — chceme najít konkrétní zápas
             * (nadcházející, pro danou kategorii). Proto vytváříme new WP_Query.
             *
             * Parametry:
             * 'post_type'      => 'zapas'  — hledáme v CPT "Zápasy"
             * 'posts_per_page' => 1         — chceme jen 1 zápas (nejbližší)
             * 'meta_key'       => 'datum_zapasu' — řadit podle meta pole "datum_zapasu"
             * 'orderby'        => 'meta_value'   — řadit podle hodnoty meta pole
             * 'order'          => 'ASC'           — vzestupně (nejbližší datum = první)
             *
             * 'tax_query' — filtrování podle TAXONOMIÍ (viz níže)
             */
            $q = new WP_Query( array(
                'post_type'      => 'zapas',
                'posts_per_page' => 1,
                'meta_key'       => 'datum_zapasu',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',

                /**
                 * tax_query — FILTROVÁNÍ PODLE TAXONOMIÍ
                 *
                 * Taxonomie = způsob třídění obsahu (jako "šuplíky").
                 * Příspěvek může být v několika taxonomiích najednou.
                 *
                 * 'relation' => 'AND' — příspěvek musí splnit OBOJÍ podmínky:
                 *   1. stav-zapasu = 'nadchazejici' (jen budoucí zápasy)
                 *   2. kategorie-tymu = $slug (jen pro aktuální kategorii)
                 *
                 * 'field' => 'slug' — porovnáváme slug (ne ID, ne název)
                 */
                'tax_query'      => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'stav-zapasu',    // Taxonomie: stav zápasu
                        'field'    => 'slug',
                        'terms'    => 'nadchazejici',   // Jen nadcházející zápasy
                    ),
                    array(
                        'taxonomy' => 'kategorie-tymu', // Taxonomie: kategorie týmu
                        'field'    => 'slug',
                        'terms'    => $slug,             // Aktuální kategorie z foreach
                    ),
                ),
            ) );

            if ( $q->have_posts() ) :
                $q->the_post(); // Načte nalezený zápas do globálních proměnných

                /**
                 * get_post_meta() — ČTENÍ VLASTNÍCH POLÍ (META DAT)
                 *
                 * Každý zápas má vlastní pole (meta data) uložená v databázi:
                 *   datum_zapasu, cas_zapasu, domaci, hoste, skore, strelci
                 *
                 * Parametry:
                 *   get_the_ID() = ID aktuálního příspěvku
                 *   'datum_zapasu' = klíč (název) meta pole
                 *   true = vrátí jednu hodnotu (ne pole)
                 *
                 * Meta pole ≠ taxonomie:
                 *   Taxonomie = třídění do skupin (sezóna, kategorie)
                 *   Meta pole = unikátní data příspěvku (datum, skóre, jméno)
                 */
                $datum  = get_post_meta( get_the_ID(), 'datum_zapasu', true );
                $cas    = get_post_meta( get_the_ID(), 'cas_zapasu', true );
                $domaci = get_post_meta( get_the_ID(), 'domaci', true );
                $hoste  = get_post_meta( get_the_ID(), 'hoste', true );

                /**
                 * Formátování data:
                 * strtotime() = převede textový datum na Unix timestamp (číslo)
                 * date_i18n() = formátuje datum s ohledem na jazyk WP
                 *   'j. n. Y' = "9. 4. 2026" (den. měsíc. rok)
                 *   _i18n = internationalization = podpora překladů názvů měsíců
                 */
                $datum_fmt = '';
                if ( $datum ) {
                    $ts = strtotime( $datum );
                    if ( $ts ) $datum_fmt = date_i18n( 'j. n. Y', $ts );
                }
                ?>
                <!-- Karta jednoho zápasu -->
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $nazev ); ?></h3>
                  <!-- Ternární operátor: podmínka ? pravda : nepravda -->
                  <p><?php echo $datum_fmt ? esc_html( $datum_fmt ) : esc_html( $datum ); ?><?php echo $cas ? ' – ' . esc_html( $cas ) : ''; ?></p>
                  <p><strong><?php echo esc_html( $domaci ); ?></strong><br>vs<br><strong><?php echo esc_html( $hoste ); ?></strong></p>
                </div>
                <?php
            else :
                // Žádný nadcházející zápas → zobrazíme prázdnou kartu s textem
                ?>
                <div class="card">
                  <h3 class="h3"><?php echo esc_html( $nazev ); ?></h3>
                  <p class="text-muted" style="font-size:13px">Žádný nadcházející zápas</p>
                </div>
                <?php
            endif;

            /**
             * wp_reset_postdata() — KLÍČOVÁ FUNKCE po vlastním WP_Query!
             *
             * WP_Query mění globální proměnnou $post.
             * Pokud ji neobnovíme, další funkce (the_title, the_content...)
             * by pracovaly se špatným příspěvkem.
             *
             * Pravidlo: po KAŽDÉM new WP_Query vždy volej wp_reset_postdata().
             */
            wp_reset_postdata();

        endforeach; // Konec foreach přes kategorie
        ?>
      </div>
    </div>
  </section>

  <!-- ================================================================
       DEKORATIVNÍ PRUHY
       ================================================================
       Čistě vizuální prvek — modré a šedé pruhy oddělující sekce.
       g-0 = Bootstrap: žádný gap (mezera) mezi sloupci.
       col-9 / col-3 = šířka sloupce v Bootstrap gridu (z 12 dílů). -->
  <div class="fluid">
    <div class="row g-0">
      <div class="col-9">
        <div class="blue-bar-p"></div>
      </div>
    </div>
  </div>

  <div class="fluid">
    <div class="row g-0">
      <div class="col-3"></div>
      <div class="col-9">
        <div class="gray-bar"></div>
      </div>
    </div>
  </div>

  <!-- ================================================================
       SEKCE 3: AKTUÁLNÍ ZPRÁVY (aktuality)
       ================================================================
       Zobrazí poslední 4 příspěvky z kategorie "aktuality",
       které ještě nevypršely (nebo nemají nastavenou expiraci). -->
  <section class="aktuality section">
    <div class="container">
      <h2 class="mb-4">Aktuální zprávy</h2>

      <?php
      /**
       * Další WP_Query — tentokrát pro aktuality (běžné příspěvky/posty).
       *
       * 'category_name'  => 'aktuality' — jen příspěvky v kategorii "aktuality"
       * 'posts_per_page' => 4           — zobrazit max 4
       *
       * 'meta_query' — FILTROVÁNÍ PODLE META POLÍ
       * ==========================================
       * Podobné jako tax_query, ale filtruje podle vlastních polí (meta dat).
       *
       * 'relation' => 'OR' — stačí splnit JEDNU z podmínek:
       *   1. Meta pole '_expiration_date' >= dnešní datum (ještě nevypršelo)
       *   2. Meta pole '_expiration_date' neexistuje (starší příspěvky bez expirace)
       *
       * 'compare' => '>=' = větší nebo rovno (datum expirace je dnes nebo později)
       * 'compare' => 'NOT EXISTS' = pole vůbec neexistuje u příspěvku
       * 'type' => 'DATE' = porovnávej jako datum (ne jako text)
       */
      $aktuality_args = array(
          'category_name'  => 'aktuality',
          'posts_per_page' => 4,
          'meta_query'     => array(
              'relation' => 'OR',
              array(
                  'key'     => '_expiration_date',
                  'value'   => date('Y-m-d'),
                  'compare' => '>=',
                  'type'    => 'DATE',
              ),
              array(
                  'key'     => '_expiration_date',
                  'compare' => 'NOT EXISTS',
              ),
          ),
      );
      $aktuality_query = new WP_Query( $aktuality_args );

      /**
       * Loop přes aktuality — stejný princip jako v index.php,
       * ale nad VLASTNÍM dotazem ($aktuality_query), ne nad hlavním.
       *
       * Rozdíl:
       *   Hlavní loop:  have_posts() / the_post()
       *   Vlastní loop: $aktuality_query->have_posts() / $aktuality_query->the_post()
       */
      if ( $aktuality_query->have_posts() ) :
          echo '<div class="row g-4">';
          while ( $aktuality_query->have_posts() ) :
              $aktuality_query->the_post();
              ?>
              <div class="col-12">
                <div class="aktualita">
                  <h4><a href="<?php the_permalink(); ?>" class="text-decoration-none">
                    <?php the_title(); ?>
                  </a></h4>
                  <!-- the_date() = vypíše datum, ALE jen jednou pro každý den
                       (pokud jsou 2 příspěvky ze stejného dne, druhý datum neuvidí).
                       Pro spolehlivý výpis je lepší get_the_date() + echo. -->
                  <p class="aktualita__datum text-muted small mb-2"><?php the_date('j. n. Y'); ?></p>
                  <?php the_excerpt(); // Krátký úryvek příspěvku ?>
                </div>
              </div>
              <?php
          endwhile;
          echo '</div>';
      else :
          echo '<p class="text-muted">Zatím nejsou žádné aktuality.</p>';
      endif;

      wp_reset_postdata(); // Obnovit globální $post po vlastním WP_Query
      ?>
    </div>
  </section>

<?php get_footer(); // Načte footer.php ?>
