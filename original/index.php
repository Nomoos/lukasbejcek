<?php get_header(); ?>
 
 
 
 
  <section class="banner">
    <img src="<?php bloginfo('template_directory'); ?>/img/banner.jpg" alt="Banner">
    <div class="banner-text">
      <h2>Fotbalový klub s tradicí od roku 1909</h2>
    </div>
  </section>

  
    <div class="container zapasy-container">
      <div class="card">
        <h3 class="h3">Muži A</h3>
        <p>08.08.2025 – 18:00</p>
        <p><strong>Rapid Plzeň</strong><br>vs<br><strong>TJ Slavoj Mýto</strong></p>
      </div>

      <div class="card">
        <h3>Muži B</h3>
        <p>08.08.2025 – 18:00</p>
        <p><strong>Rapid Plzeň</strong><br>vs<br><strong>TJ Slavoj Mýto</strong></p>
      </div>

      <div class="card">
        <h3>Dorost</h3>
        <p>08.08.2025 – 18:00</p>
        <p><strong>TJ Slavoj Mýto</strong><br>vs<br><strong>FK Bohemia Kaznějov</strong></p>
      </div>

      <div class="card">
        <h3>Starší žáci</h3>
        <p>08.08.2025 – 18:00</p>
        <p><strong>Rapid Plzeň</strong><br>vs<br><strong>TJ Slavoj Mýto</strong></p>
      </div>
    </div>




    

  <div class="fluid">
    <div class="row">
      <div class="col-9">
          <div class="blue-bar-p"></div>
      </div>
    </div>
  </div>


  <br>


  <div class="fluid">
    <div class="row">
      <div class="col-3"></div>
      <div class="col-9">
          <div class="gray-bar"></div>
      </div>
    </div>
  </div>




  <div class="container">
    <h2 class="mb-4">Aktuální zprávy</h2>

<?php
  $args = array(
        'category_name' => 'aktuality',
        'posts_per_page' => 5
        );
        $aktuality_query = new WP_Query( $args );
        if( $aktuality_query->have_posts() ):
        while( $aktuality_query->have_posts() ): $aktuality_query->the_post();
            ?>
            <div class="row g-4">
                <div class="col-6">
                  <div class="bg-light border p-3 rounded">
                    <h5 class="text-primary"><?php the_title(); ?></h5>
                    <p><?php the_content(); ?></p>
                  </div>
            </div>

            <?php
       
        endwhile;
        endif;
        wp_reset_postdata();
?>

  <?php get_footer(); ?>