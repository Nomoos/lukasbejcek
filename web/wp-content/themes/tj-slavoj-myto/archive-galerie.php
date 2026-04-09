<?php
/**
 * archive-galerie.php — ARCHIV (VÝPIS) FOTOALB
 * ================================================
 * Zobrazuje mřížku fotoalb s filtrováním podle kategorie a sezóny.
 * WordPress použije na URL: /galerie/ (archiv CPT 'galerie').
 *
 * SPECIFIKA GALERIE:
 * - Kategorie jsou definovány PEVNÝM polem (ne z DB) — obsahují
 *   virtuální slug 'zaci' (sloučení starsi-zaci + mladsi-zaci)
 * - Na rozdíl od archive-zapas.php je výchozí filtr prázdný (vše)
 * - <noscript> tlačítko = záloha pro uživatele bez JavaScriptu
 */

get_header();

// Filtry z URL — výchozí = prázdný string (zobrazit vše)
$filtr_kategorie = isset($_GET['kategorie']) ? sanitize_text_field(wp_unslash($_GET['kategorie'])) : '';
$filtr_sezona    = isset($_GET['sezona'])    ? sanitize_text_field(wp_unslash($_GET['sezona']))    : '';

/**
 * PEVNÝ SEZNAM KATEGORIÍ pro galerii.
 *
 * Na rozdíl od archive-zapas.php (kde se kategorie berou z DB přes get_terms()),
 * tady je pole definované přímo v kódu. Důvod:
 * - Slug 'zaci' reálně v DB neexistuje (je "virtuální")
 * - V galerii chceme jiný výběr než v zápasech (vč. Stará garda)
 */
$galerie_kategorie = array(
    'muzi-a'           => 'Muži A',
    'muzi-b'           => 'Muži B',
    'stara-garda'      => 'Stará garda',
    'dorost'           => 'Dorost',
    'zaci'             => 'Žáci',             // Virtuální: starsi-zaci + mladsi-zaci
    'starsi-pripravka' => 'Starší přípravka',
    'mladsi-pripravka' => 'Mladší přípravka',
    'mini-pripravka'   => 'Minipřípravka',
);

$dostupne_sezony = get_terms(array(
    'taxonomy'   => 'sezona',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'DESC',
));
?>

<section class="section">
  <div class="container">
    <header class="page-title">
      <h1 class="page-title__h1">Galerie</h1>
      <p class="page-title__subtitle">
        Fotografie z klubového života<br><?php echo esc_html(get_bloginfo('name')); ?>
      </p>
    </header>

    <!-- FILTRY — selecty s auto-submit po změně výběru -->
    <form method="get" action="<?php echo esc_url(get_post_type_archive_link('galerie')); ?>" aria-label="Filtrování galerie">
      <div class="row g-2 mb-4">

        <div class="col-12 col-md-4">
          <label class="visually-hidden" for="f-kategorie">Kategorie týmu</label>
          <select id="f-kategorie" name="kategorie" class="form-select filter-select-team" onchange="this.form.submit()">
            <option value="">Všechny kategorie</option>
            <?php foreach ($galerie_kategorie as $slug => $nazev) : ?>
              <option value="<?php echo esc_attr($slug); ?>" <?php selected($filtr_kategorie, $slug); ?>>
                <?php echo esc_html($nazev); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12 col-md-4">
          <label class="visually-hidden" for="f-sezona">Sezóna</label>
          <select id="f-sezona" name="sezona" class="form-select filter-select-season" onchange="this.form.submit()">
            <option value="">Všechny sezóny</option>
            <?php if (!is_wp_error($dostupne_sezony) && !empty($dostupne_sezony)) : foreach ($dostupne_sezony as $s) : ?>
              <option value="<?php echo esc_attr($s->slug); ?>" <?php selected($filtr_sezona, $s->slug); ?>>
                Sezóna <?php echo esc_html($s->name); ?>
              </option>
            <?php endforeach; endif; ?>
          </select>
        </div>

        <!-- <noscript> = obsah zobrazený JEN pokud má uživatel vypnutý JavaScript.
             Bez JS nefunguje onchange auto-submit → potřebujeme klasické tlačítko.
             Progresivní vylepšení: web funguje i bez JS, jen s horším UX. -->
        <noscript>
          <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-primary">Filtrovat</button>
          </div>
        </noscript>

      </div>
    </form>
  </div>
</section>

<!-- MODRÝ PRUH S LOGEM -->
<div class="container-fluid px-0">
  <div class="row align-items-center g-0">
    <div class="col-5"><div class="blue-bar-p"></div></div>
    <div class="col-2 d-flex justify-content-center">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo-tjslavoj.png"
           alt="TJ Slavoj Mýto" height="50">
    </div>
    <div class="col-5"><div class="blue-bar-l"></div></div>
  </div>
</div>

<!-- GRID GALERIE -->
<section class="section">
  <div class="container">
    <div class="row g-4">
      <?php
      $args = array(
          'post_type'      => 'galerie',
          'posts_per_page' => 24,
      );

      $tax_query = array();

      if ($filtr_kategorie) {
          /**
           * VIRTUÁLNÍ SLUG 'zaci':
           * Slug 'zaci' v databázi neexistuje. Když ho uživatel vybere,
           * přeložíme ho na DVA reálné slugy: 'starsi-zaci' a 'mladsi-zaci'.
           * WP_Query pak hledá alba přiřazená k LIBOVOLNÉMU z nich.
           *
           * Ternární operátor:
           *   podmínka ? hodnota_pokud_true : hodnota_pokud_false
           */
          $terms_pro_query = ($filtr_kategorie === 'zaci')
              ? array('starsi-zaci', 'mladsi-zaci')
              : $filtr_kategorie;
          $tax_query[] = array(
              'taxonomy' => 'kategorie-tymu',
              'field'    => 'slug',
              'terms'    => $terms_pro_query,
          );
      }

      if ($filtr_sezona) {
          $tax_query[] = array(
              'taxonomy' => 'sezona',
              'field'    => 'slug',
              'terms'    => $filtr_sezona,
          );
      }

      if (!empty($tax_query)) {
          $tax_query['relation'] = 'AND';
          $args['tax_query'] = $tax_query;
      }

      $galerie_query = new WP_Query($args);

      if ($galerie_query->have_posts()) :
          while ($galerie_query->have_posts()) :
              $galerie_query->the_post();
              ?>
              <div class="col-6 col-md-3 text-center">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium', array(
                        'class'   => 'img-fluid gallery-img',
                        'loading' => 'lazy',
                    )); ?>
                  <?php else : ?>
                    <div class="gallery-placeholder mb-2"></div>
                  <?php endif; ?>
                  <p class="mt-2 text-dark"><?php the_title(); ?></p>
                </a>
              </div>
              <?php
          endwhile;
      else :
          ?>
          <div class="col-12">
            <div class="empty-state">
              <svg class="empty-state__icon" xmlns="http://www.w3.org/2000/svg"
                   width="48" height="48" fill="none" stroke="currentColor"
                   stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                   aria-hidden="true" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <polyline points="21 15 16 10 5 21"/>
              </svg>
              <p class="empty-state__title">Žádná fotoalba nebyla nalezena</p>
              <p class="empty-state__text">Zkuste změnit filtry nebo se vraťte později.</p>
            </div>
          </div>
          <?php
      endif;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
