<?php
/**
 * page-aktuality.php — STRÁNKA AKTUALIT (novinky klubu)
 * =======================================================
 * WordPress použije tuto šablonu pro stránku se slugem "aktuality".
 *
 * POJMENOVÁNÍ: page-{slug}.php → WP automaticky přiřadí šablonu
 * ke stránce, jejíž slug (URL) odpovídá názvu souboru.
 *
 * "Template Name: Aktuality" v komentáři nahoře navíc registruje
 * šablonu v Administraci → Stránky → Atributy → Šablona,
 * takže ji admin může ručně přiřadit i jiné stránce.
 *
 * Zobrazuje: příspěvky z kategorie "aktuality" s vlastním stránkováním.
 */

get_header(); // Načte header.php

/**
 * VLASTNÍ STRÁNKOVÁNÍ přes GET parametr.
 *
 * $_GET['stranka'] = hodnota z URL, např. ?stranka=2
 * absint()         = převede na kladné celé číslo (absolute integer)
 *                    → ochrana proti záporným číslům a nečíselným hodnotám
 * max(1, ...)      = minimálně stránka 1 (nikdy 0 nebo záporné)
 *
 * isset() = existuje parametr v URL? Pokud ne → výchozí stránka 1.
 */
$paged = isset( $_GET['stranka'] ) ? max( 1, absint( $_GET['stranka'] ) ) : 1;

/**
 * WP_Query pro aktuality.
 *
 * 'post_type'      => 'post'       — standardní WordPress příspěvky (ne CPT)
 * 'category_name'  => 'aktuality'  — filtr: jen příspěvky v kategorii "aktuality"
 * 'posts_per_page' => 10           — max 10 na stránku
 * 'paged'          => $paged       — která stránka (z GET parametru)
 * 'orderby'        => 'date'       — řadit podle data publikace
 * 'order'          => 'DESC'       — sestupně (nejnovější první)
 */
$aktuality_query = new WP_Query( array(
    'post_type'      => 'post',
    'category_name'  => 'aktuality',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<!-- ═══════════════════════════════════════════
     ZÁHLAVÍ STRÁNKY — nadpis + popis
     ═══════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <header class="page-title">
      <!-- get_the_title(ID) = vrátí název stránky podle ID
           get_queried_object_id() = ID aktuálně zobrazené stránky -->
      <h1 class="page-title__h1"><?php echo esc_html( get_the_title( get_queried_object_id() ) ); ?></h1>
      <p class="page-title__subtitle"><?php
          /**
           * get_the_excerpt(ID) = vrátí výňatek stránky (nastavitelný v editoru).
           * wp_strip_all_tags() = odstraní všechny HTML tagy (čistý text).
           * sprintf() = formátování řetězce (%s = placeholder pro název webu).
           * Pokud stránka nemá výňatek → zobrazí "Nejnovější zprávy TJ Slavoj Mýto".
           */
          $sub = get_the_excerpt( get_queried_object_id() );
          echo $sub ? esc_html( wp_strip_all_tags( $sub ) ) : esc_html( sprintf( 'Nejnovější zprávy %s', get_bloginfo( 'name' ) ) );
      ?></p>
    </header>
  </div>
</section>

<!-- ═══════════════════════════════════════════
     SEZNAM AKTUALIT
     ═══════════════════════════════════════════ -->
<!-- esc_attr_e() = escapuje pro HTML atribut + přeloží text (bezpečné aria-label) -->
<section class="section aktuality" aria-label="<?php esc_attr_e( 'Seznam aktualit', 'tj-slavoj-myto' ); ?>">
  <div class="container">
    <div class="aktuality-box">
      <?php if ( $aktuality_query->have_posts() ) : ?>

        <?php while ( $aktuality_query->have_posts() ) : $aktuality_query->the_post(); ?>
          <?php $has_thumb = has_post_thumbnail(); // Má příspěvek náhledový obrázek? ?>

          <!-- <article> = sémantický tag pro jeden článek
               Třída 'druhy' se přidá jen když má obrázek (jiný CSS layout)
               id="post-{ID}" = unikátní identifikátor pro CSS/JS -->
          <article class="aktualita<?php echo $has_thumb ? ' druhy' : ''; ?>"
                   id="post-<?php the_ID(); ?>">

            <?php if ( $has_thumb ) : ?>
              <!-- aria-hidden="true" = skryje obrázek před čtečkami (je dekorativní,
                   odkaz na článek je v nadpisu níže)
                   tabindex="-1" = obrázek nelze fokusovat klávesou Tab
                   → přístupnost: nevidomý uživatel neprojde odkaz dvakrát -->
              <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail( 'medium', array( 'alt' => '' ) ); ?>
              </a>
            <?php endif; ?>

            <div class="text">
              <p class="aktualita__date text-muted mb-1">
                <!-- <time> = HTML5 sémantický tag pro datum/čas
                     datetime="2026-04-09" = strojově čitelný formát (ISO 8601)
                     Vnitřní text = lidsky čitelný český formát -->
                <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
                  <?php echo esc_html( get_the_date( 'j. n. Y' ) ); ?>
                </time>
              </p>
              <h4>
                <a href="<?php the_permalink(); ?>" class="aktualita__link text-decoration-none">
                  <?php the_title(); ?>
                </a>
              </h4>
              <p><?php the_excerpt(); ?></p>

              <!-- sprintf() + __() = přeložitelný text s proměnnou.
                   __() vrací překlad, sprintf() dosadí %s = název článku.
                   aria-label = čtečka přečte "Číst více: Turnaj v Mýtě"
                   místo generického "Číst více" (lepší přístupnost). -->
              <a href="<?php the_permalink(); ?>"
                 class="btn btn--outline btn-sm btn-outline-primary"
                 aria-label="<?php echo esc_attr( sprintf( __( 'Číst více: %s', 'tj-slavoj-myto' ), get_the_title() ) ); ?>">
                Číst více
              </a>
            </div>
          </article>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); // Obnovit globální $post ?>

        <?php
        /**
         * STRÁNKOVÁNÍ (pagination)
         *
         * max_num_pages = kolik stránek celkem WP_Query vrátil
         * (závisí na posts_per_page a celkovém počtu nalezených příspěvků)
         *
         * paginate_links() = WP funkce generující HTML s čísly stránek.
         *
         * remove_query_arg('stranka') = odstraní ?stranka=X z aktuální URL
         * → vytvoří čistou base URL, ke které pak přidáme nové číslo stránky
         *
         * wp_kses_post() = bezpečný výpis HTML — povolí jen HTML tagy
         * povolené v příspěvcích (odkazy, strong, em...), odstraní nebezpečné.
         */
        $total_pages = $aktuality_query->max_num_pages;
        if ( $total_pages > 1 ) :
            $base_url   = remove_query_arg( 'stranka' );
            $sep        = strpos( $base_url, '?' ) !== false ? '&' : '?';
            $pagination = paginate_links( array(
                'base'      => $base_url . $sep . 'stranka=%#%',
                'format'    => '',
                'current'   => $paged,
                'total'     => $total_pages,
                'mid_size'  => 2,
                'prev_text' => '&larr; ' . esc_html__( 'Předchozí', 'tj-slavoj-myto' ),
                'next_text' => esc_html__( 'Další', 'tj-slavoj-myto' ) . ' &rarr;',
            ) );
            if ( $pagination ) :
        ?>
          <nav class="pagination-nav" aria-label="<?php esc_attr_e( 'Stránkování', 'tj-slavoj-myto' ); ?>">
            <?php echo wp_kses_post( $pagination ); ?>
          </nav>
        <?php
            endif;
        endif;
        ?>

      <?php else : ?>
        <!-- Prázdný stav — žádné aktuality nalezeny
             role="status" = ARIA role: čtečka oznámí tento obsah -->
        <div class="empty-state" role="status">
          <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
               width="48" height="48" fill="none" stroke="currentColor"
               stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
               aria-hidden="true" viewBox="0 0 24 24">
            <path d="M4 4h16v16H4z"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="4" y1="10" x2="20" y2="10"/>
          </svg>
          <p class="empty-state__title">Žádné aktuality nenalezeny</p>
          <p class="empty-state__text">Zatím nebyly přidány žádné příspěvky.</p>
        </div>
      <?php endif; ?>
    </div><!-- /.aktuality-box -->
  </div>
</section>

<?php get_footer(); ?>
