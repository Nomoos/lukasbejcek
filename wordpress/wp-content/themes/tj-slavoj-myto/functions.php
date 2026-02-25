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
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    wp_enqueue_style('slavoj-style', get_stylesheet_uri(), array('bootstrap'), '1.0');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);
}
add_action('wp_enqueue_scripts', 'slavoj_enqueue_scripts');

/**
 * Záložní menu pokud není nastaveno v administraci
 */
function slavoj_fallback_menu() {
    echo '<ul class="nav">';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">Domů</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/zapasy/')) . '">Zápasy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/tymy/')) . '">Týmy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/galerie/')) . '">Galerie</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/historie/')) . '">Historie</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/sponzori/')) . '">Sponzoři</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/kontakty/')) . '">Kontakty</a></li>';
    echo '</ul>';
}

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
