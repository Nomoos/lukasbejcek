<?php get_header(); ?>¨



<div class="container py-5">
    <h2 class="mb-0">Galerie</h2>
    <p class="text-muted mb-4">Fotografie z klubového života TJ Slavoj Mýto</p>

   <!-- FILTRY -->
   <div class="d-flex gap-3 mb-4">
    <select class="form-select bg-light" style="width: 11%;">
      <option>Muži A</option>
      <option>Muži B</option>
      <option>Dorost</option>
      <option>Starší žáci</option>
    </select>

    <select class="form-select bg-light" style="width: 15%;">
      <option>Sezóna 2025/26</option>
      <option>Sezóna 2024/25</option>
    </select>
  </div>


</div>
    

    <!-- MODRÝ PRUH S LOGEM -->
    <div class="fluid">
        <div class="row">
          
              <div class="col-5">
                  <div class="blue-bar-p"></div>
              </div>
        
            <div class="col-1">
                <img src="" alt="TJ Slavoj Mýto" class="">
            </div>
        
              <div class="col-6">
                  <div class="blue-bar-l"></div>
              </div>
        </div>
        </div>
      
    
<div class="container py-5">
    <!-- GRID GALERIE -->
    <div class="row g-4">
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Mýto vs Lhota</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Horní Bříza vs Mýto</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Mýto vs Lhota</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Horní Bříza vs Mýto</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Mýto vs Lhota</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Horní Bříza vs Mýto</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Mýto vs Lhota</p>
      </div>
      <div class="col-6 col-md-3 text-center">
        <div class="gallery-card mb-2"></div>
        <p>Horní Bříza vs Mýto</p>
      </div>
    </div>
  </div>
  <?php



$args = array(
  'category_name' => 'galerie',
  'posts_per_page' => 5
);

$galerie_query = new WP_Query($args);

if ($galerie_query->have_posts()):
  while ($galerie_query->have_posts()):
    $galerie_query->the_post();
?>

  <div class="col-6 col-md-3 text-center">

    <a href="<?php the_permalink(); ?>" class="text-decoration-none">

      <?php if (has_post_thumbnail()): ?>
        <?php the_post_thumbnail('medium', [
          'class' => 'img-fluid gallery-img'
        ]); ?>
      <?php else: ?>
        <div class="gallery-placeholder"></div>
      <?php endif; ?>

      <p class="mt-2 text-dark"><?php the_title(); ?></p>

    </a>

  </div>

<?php
  endwhile;
endif;
wp_reset_postdata();
?>


  <?php get_footer(); ?>