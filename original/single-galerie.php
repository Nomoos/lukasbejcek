<?php get_header(); ?>

<div class="container py-5">


  <h2 class="text-center fw-bold mb-5">
    <?php the_title(); ?>
  </h2>

 
  <?php
  $images = get_field('galerie_fotky');
  if ($images):
  ?>
  <div class="row g-4">

    <?php foreach ($images as $image): ?>
      <div class="col-6 col-md-3 text-center">
        <img
          src="<?php echo esc_url($image['sizes']['medium']); ?>"
          alt="<?php echo esc_attr($image['alt']); ?>"
          class="img-fluid fotky"
        >
      </div>
    <?php endforeach; ?>

  </div>
  <?php endif; ?>

</div>


<?php get_footer(); ?>