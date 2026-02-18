<?php get_header(); ?>


<div class="container py-5 text-center">

<h2 class="fw-bold mb-5">Výbor klubu TJ Slavoj Mýto</h2>

<div class="row g-4 justify-content-center">

<?php

$args = array(
'category_name' => 'kontakty',
'posts_per_page' => 5
);
$kontakty_query = new WP_Query( $args );
if( $kontakty_query->have_posts() ):
while( $kontakty_query->have_posts() ): $kontakty_query->the_post();
    ?>
    <div class="col-md-4 col-lg-3">
        <div class="committee-card p-4">
          <div class="committee-img-wrapper">
            <img src="<?php bloginfo('template_directory'); ?>/img/logo.png" class="committee-img" alt="">
          </div>
          <h4 class="mb-1"><?php the_title();  ?></h4>
          <p class="text-muted mb-3"><?php the_content(); ?></p>

          <p class="mb-0"><strong>Tel.:</strong> +420 737 259 684</p>
          <p class="mb-0"><strong>email:</strong> tjslavojmyto@seznam.cz</p>
        </div>
      </div>

    <?php
endwhile;
endif;
wp_reset_postdata();
?>
  

</div>

</div>



<?php get_footer(); ?>