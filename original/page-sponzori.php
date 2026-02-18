<?php get_header(); ?>


<div class="container py-5 text-center">

<h2 class="fw-bold mb-5">Sponzoři</h2>

<div class="row g-4 justify-content-center">

<?php

$args = array(
'category_name' => 'sponzori',
'posts_per_page' => 5
);
$sponzori_query = new WP_Query( $args );
if( $sponzori_query->have_posts() ):
while( $sponzori_query->have_posts() ): $sponzori_query->the_post();
    ?>
    <div>
    <div class="col-md-4 col-lg-3">
        <div class="committee-card p-4">
          <div class="committee-img-wrapper">
            <img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="committee-img" alt="">
          </div>
         
        </div>
      </div>
      <h5 class="mb-1"><?php the_title();  ?></h5>
    </div>
    <?php
endwhile;
endif;
wp_reset_postdata();
?>
  

</div>

</div>


<?php get_footer(); ?>