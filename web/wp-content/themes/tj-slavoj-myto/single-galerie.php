<?php
/**
 * single-galerie.php — DETAIL FOTOALBA
 * =======================================
 * WordPress použije na URL: /galerie/nazev-alba/
 *
 * Zobrazuje: název alba, mřížku fotografií a LIGHTBOX (prohlížeč fotek).
 *
 * ZDROJE FOTEK (2 možnosti):
 * 1. ACF galerie pole (pokud je nainstalován plugin ACF)
 *    → get_field('galerie_fotky') vrací pole obrázků
 * 2. Obsah příspěvku (fallback) — fotky vložené v editoru
 *    → the_content() vypíše WordPress galerii
 *
 * LIGHTBOX = překryvná vrstva na prohlížení fotek přes celou obrazovku.
 * Implementován čistým vanilla JS (bez knihoven).
 * Podporuje: kliknutí na fotku, šipky vlevo/vpravo, Escape, klik mimo.
 */

get_header();
?>

<div class="container py-5">
  <h2 class="text-center fw-bold mb-2"><?php the_title(); ?></h2>

  <?php
  $kategorie_tymu = get_the_terms(get_the_ID(), 'kategorie-tymu');
  $sezona_terms   = get_the_terms(get_the_ID(), 'sezona');
  $kategorie_nazev = '';
  $sezona          = '';
  if (!empty($kategorie_tymu) && !is_wp_error($kategorie_tymu)) {
      $kategorie_nazev = esc_html($kategorie_tymu[0]->name);
  }
  if (!empty($sezona_terms) && !is_wp_error($sezona_terms)) {
      $sezona = esc_html($sezona_terms[0]->name);
  }
  if ($kategorie_nazev || $sezona) :
  ?>
    <p class="text-center text-muted mb-4">
      <?php echo $kategorie_nazev; ?>
      <?php if ($kategorie_nazev && $sezona) echo ' – '; ?>
      <?php echo $sezona; ?>
    </p>
  <?php endif; ?>

  <?php
  // Podpora pro ACF galerie pole
  /**
   * function_exists('get_field') = je nainstalován plugin ACF (Advanced Custom Fields)?
   * Pokud ano → načteme fotky z ACF galerie pole.
   * Pokud ne → $images = null → použijeme fallback (obsah editoru).
   *
   * Tato kontrola zabrání PHP fatal error, pokud ACF není aktivní.
   */
  if (function_exists('get_field')) {
      $images = get_field('galerie_fotky');
  } else {
      $images = null;
  }

  if ($images) :
  ?>
  <div class="row g-4">
    <?php foreach ($images as $index => $image) : ?>
      <div class="col-6 col-md-3 text-center">
        <img
          src="<?php echo esc_url($image['sizes']['medium']); ?>"
          alt="<?php echo esc_attr($image['alt']); ?>"
          class="img-fluid fotky lightbox-trigger gallery-trigger"
          data-full="<?php echo esc_url($image['url']); ?>"
          data-index="<?php echo (int) $index; ?>"
        >
      </div>
    <?php endforeach; ?>
  </div>
  <?php else : ?>
    <?php
    // Záložní zobrazení z obsahu příspěvku
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
    endif;
    ?>
  <?php endif; ?>
</div>

<!-- ================================================================
     LIGHTBOX — překryvná vrstva pro prohlížení fotek na celou obrazovku
     ================================================================
     Skrytá ve výchozím stavu (CSS: display:none / opacity:0).
     JS přidá třídu 'active' → lightbox se zobrazí.
     &times; = × (křížek), &#10094; = ‹ (šipka vlevo), &#10095; = › (šipka vpravo) -->
<div class="lightbox-overlay" id="lightbox">
  <span class="lightbox-close" id="lightbox-close">&times;</span>
  <span class="lightbox-prev" id="lightbox-prev">&#10094;</span>
  <img src="" alt="Fotografie" id="lightbox-img">
  <span class="lightbox-next" id="lightbox-next">&#10095;</span>
</div>

<script>
/**
 * LIGHTBOX — VANILLA JAVASCRIPT (bez knihoven)
 *
 * IIFE pattern: (function() { ... })();
 * → kód se spustí ihned, proměnné jsou lokální (nezanáší window).
 *
 * PRINCIP:
 * 1. Najdi všechny fotky s třídou .lightbox-trigger
 * 2. Z každé přečti data-full (URL plné velikosti) do pole images[]
 * 3. Klik na fotku → zobraz lightbox s danou fotkou
 * 4. Šipky / klávesy → posun na další/předchozí
 * 5. Escape / klik mimo / křížek → zavři lightbox
 */
(function() {
  // querySelectorAll = najde všechny elementy odpovídající CSS selektoru
  var triggers = document.querySelectorAll('.lightbox-trigger');
  var overlay = document.getElementById('lightbox');
  var lightboxImg = document.getElementById('lightbox-img');
  var closeBtn = document.getElementById('lightbox-close');
  var prevBtn = document.getElementById('lightbox-prev');
  var nextBtn = document.getElementById('lightbox-next');
  var currentIndex = 0;  // Index aktuálně zobrazené fotky
  var images = [];       // Pole URL všech fotek (plná velikost)

  // Naplnění pole images[] z data-full atributů
  triggers.forEach(function(el) {
    images.push(el.getAttribute('data-full'));
  });

  /**
   * showImage() — zobrazí fotku na daném indexu.
   * Cyklické procházení: po poslední fotce → první, před první → poslední.
   */
  function showImage(index) {
    if (index < 0) index = images.length - 1;   // Cyklus zpět
    if (index >= images.length) index = 0;       // Cyklus vpřed
    currentIndex = index;
    lightboxImg.src = images[currentIndex];      // Nastaví src obrázku
    overlay.classList.add('active');              // Zobrazí lightbox (CSS)
  }

  // Klik na fotku v mřížce → otevři lightbox na jejím indexu
  triggers.forEach(function(el) {
    el.addEventListener('click', function() {
      showImage(parseInt(this.getAttribute('data-index'), 10));
    });
  });

  // Zavření lightboxu
  closeBtn.addEventListener('click', function() {
    overlay.classList.remove('active');
  });

  // Navigace šipkami (HTML tlačítka)
  prevBtn.addEventListener('click', function() {
    showImage(currentIndex - 1);
  });

  nextBtn.addEventListener('click', function() {
    showImage(currentIndex + 1);
  });

  // Klik na overlay (mimo obrázek) → zavře lightbox
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {  // e.target = element, na který se kliklo
      overlay.classList.remove('active');
    }
  });

  // Klávesové zkratky: Escape = zavřít, šipky = navigace
  document.addEventListener('keydown', function(e) {
    if (!overlay.classList.contains('active')) return; // Lightbox není otevřený
    if (e.key === 'Escape') overlay.classList.remove('active');
    if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
    if (e.key === 'ArrowRight') showImage(currentIndex + 1);
  });
})();
</script>

<?php get_footer(); ?>
