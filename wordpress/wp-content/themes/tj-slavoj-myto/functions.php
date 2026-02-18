<?php
/**
 * TJ Slavoj Mýto - functions.php
 * Registrace menu, podpora šablony a pomocné funkce
 */

// Registrace navigačního menu
function slavoj_menus() {
    register_nav_menus(array(
        'main_menu' => 'Hlavní menu',
    ));
}
add_action('after_setup_theme', 'slavoj_menus');

// Podpora šablony
function slavoj_theme_support() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'slavoj_theme_support');

// Načtení stylů a skriptů
function slavoj_enqueue_scripts() {
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('slavoj-style', get_stylesheet_uri());
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'slavoj_enqueue_scripts');

/**
 * Výpis soupisky hráčů podle kategorie
 * Seřazeno podle čísla dresu a roku narození
 */
function slavoj_vypis_soupisku($kategorie) {
    $args = array(
        'category_name'  => $kategorie,
        'posts_per_page' => -1,
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'cislo',
        'order'          => 'ASC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            ?>
            <div class="col-6">
                <?php echo esc_html(get_post_meta(get_the_ID(), 'cislo', true)); ?>
                <?php the_title(); ?>
                &nbsp;&nbsp; narozený:
                <?php echo esc_html(get_post_meta(get_the_ID(), 'rok_narozeni', true)); ?>
            </div>
            <?php
        endwhile;
    endif;

    wp_reset_postdata();
}
