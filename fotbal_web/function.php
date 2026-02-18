<?php

function moje_sablona_menus() {
 
    register_nav_menus(array(
    'main_menu' => 'Hlavní menu', 
    ));
    }
    add_action('after_setup_theme', 'moje_sablona_menus');


   
    function slavoj_vypis_soupisku($kategorie) {
    
      $args = array(
        'category_name' => $kategorie,
        'posts_per_page' => -1,
        'orderby' => 'meta_value_num',
        'meta_key' => 'cislo',
        'order' => 'ASC'
      );
    
      $query = new WP_Query($args);
    
      if ($query->have_posts()):
        while ($query->have_posts()):
          $query->the_post();
          ?>
            <div class="col-6">
              <?php echo get_post_meta(get_the_ID(), 'cislo', true); ?>
              <?php the_title(); ?>
              &nbsp;&nbsp; narozený:
              <?php echo get_post_meta(get_the_ID(), 'rok_narozeni', true); ?>
            </div>
          <?php
        endwhile;
      endif;
    
      wp_reset_postdata();
    }
    