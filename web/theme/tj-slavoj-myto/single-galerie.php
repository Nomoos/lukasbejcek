<?php
/**
 * Template pro jednotlivé foto album (galerie příspěvek)
 * Obsahuje: název alba, mřížka fotografií, lightbox pro prohlížení
 */
get_header();
?>

<div class="container py-5">
  <h2 class="text-center fw-bold mb-2"><?php the_title(); ?></h2>

  <?php
  $tym = esc_html(get_post_meta(get_the_ID(), 'tym', true));
  $sezona = esc_html(get_post_meta(get_the_ID(), 'sezona', true));
  if ($tym || $sezona) :
  ?>
    <p class="text-center text-muted mb-4">
      <?php echo $tym; ?>
      <?php if ($tym && $sezona) echo ' – '; ?>
      <?php echo $sezona; ?>
    </p>
  <?php endif; ?>

  <?php
  // Podpora pro ACF galerie pole
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
          class="img-fluid fotky lightbox-trigger"
          data-full="<?php echo esc_url($image['url']); ?>"
          data-index="<?php echo (int) $index; ?>"
          style="cursor: pointer;"
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

<!-- LIGHTBOX -->
<div class="lightbox-overlay" id="lightbox">
  <span class="lightbox-close" id="lightbox-close">&times;</span>
  <span class="lightbox-prev" id="lightbox-prev">&#10094;</span>
  <img src="" alt="Fotografie" id="lightbox-img">
  <span class="lightbox-next" id="lightbox-next">&#10095;</span>
</div>

<script>
(function() {
  var triggers = document.querySelectorAll('.lightbox-trigger');
  var overlay = document.getElementById('lightbox');
  var lightboxImg = document.getElementById('lightbox-img');
  var closeBtn = document.getElementById('lightbox-close');
  var prevBtn = document.getElementById('lightbox-prev');
  var nextBtn = document.getElementById('lightbox-next');
  var currentIndex = 0;
  var images = [];

  triggers.forEach(function(el) {
    images.push(el.getAttribute('data-full'));
  });

  function showImage(index) {
    if (index < 0) index = images.length - 1;
    if (index >= images.length) index = 0;
    currentIndex = index;
    lightboxImg.src = images[currentIndex];
    overlay.classList.add('active');
  }

  triggers.forEach(function(el) {
    el.addEventListener('click', function() {
      showImage(parseInt(this.getAttribute('data-index'), 10));
    });
  });

  closeBtn.addEventListener('click', function() {
    overlay.classList.remove('active');
  });

  prevBtn.addEventListener('click', function() {
    showImage(currentIndex - 1);
  });

  nextBtn.addEventListener('click', function() {
    showImage(currentIndex + 1);
  });

  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      overlay.classList.remove('active');
    }
  });

  document.addEventListener('keydown', function(e) {
    if (!overlay.classList.contains('active')) return;
    if (e.key === 'Escape') overlay.classList.remove('active');
    if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
    if (e.key === 'ArrowRight') showImage(currentIndex + 1);
  });
})();
</script>

<?php get_footer(); ?>
