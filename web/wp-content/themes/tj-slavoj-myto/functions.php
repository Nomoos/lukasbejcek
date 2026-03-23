<?php
/**
 * TJ Slavoj Mýto - functions.php
 * Registrace menu, CPT, taxonomií, meta boxů a pomocných funkcí
 */

// Výchozí URL embedu mapy (OpenStreetMap – Mýto, okr. Rokycany)
define( 'TJSM_DEFAULT_MAP_URL', 'https://www.openstreetmap.org/export/embed.html?bbox=13.710%2C49.748%2C13.750%2C49.760&layer=mapnik&marker=49.7541%2C13.7308' );

// =====================================================================
// SETUP TÉMATU
// =====================================================================

function slavoj_menus() {
    register_nav_menus(array(
        'primary'   => 'Hlavní menu',
        'footer'    => 'Patičkové menu',
        /* Zpětná kompatibilita se starším názvem */
        'main_menu' => 'Hlavní menu (legacy)',
    ));
}
add_action('after_setup_theme', 'slavoj_menus');

function slavoj_theme_support() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('responsive-embeds');
    add_image_size('gallery-thumb', 400, 400, true);
}
add_action('after_setup_theme', 'slavoj_theme_support');

// Načtení stylů a skriptů
function slavoj_enqueue_scripts() {
    $ver = wp_get_theme()->get('Version');

    // Bootstrap CSS (grid, utilities) + Bootstrap JS bundle (navbar collapse – bez custom JS)
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true);

    // Komponenty CSS – načítány zvlášť (spolehlivější než @import uvnitř main.css)
    $css = get_template_directory_uri() . '/assets/css/';
    wp_enqueue_style('slavoj-utilities',  $css . 'utilities.css',            array('bootstrap'), $ver);
    wp_enqueue_style('slavoj-header',     $css . 'components/header.css',    array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-nav-mobile', $css . 'components/nav-mobile.css', array('slavoj-header'), $ver);
    wp_enqueue_style('slavoj-buttons',    $css . 'components/buttons.css',   array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-hero',       $css . 'components/hero.css',      array('slavoj-utilities'), $ver);
    wp_enqueue_style('slavoj-cards',      $css . 'components/cards.css',     array('slavoj-utilities'), $ver);
    // Hlavní šablona CSS
    wp_enqueue_style('slavoj-main', $css . 'main.css', array('slavoj-utilities', 'slavoj-buttons', 'slavoj-cards', 'slavoj-header', 'slavoj-hero', 'slavoj-nav-mobile'), $ver);
}
add_action('wp_enqueue_scripts', 'slavoj_enqueue_scripts');

// ──────────────────────────────────────────────────────────────────────
// BOOTSTRAP TŘÍDY PRO wp_nav_menu() (primary lokace)
// Přidá nav-item na <li> a nav-link na <a> – kompatibilní s Bootstrap navbar.
// ──────────────────────────────────────────────────────────────────────

/**
 * Přidá Bootstrap třídu 'nav-item' na <li> položky hlavního menu.
 */
function slavoj_bootstrap_nav_item_class( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'slavoj_bootstrap_nav_item_class', 10, 4 );

/**
 * Přidá Bootstrap třídy 'nav-link' (a 'active' pro aktuální stránku) na <a>.
 */
function slavoj_bootstrap_nav_link_attrs( $attrs, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $cls = isset( $attrs['class'] ) ? $attrs['class'] . ' nav-link' : 'nav-link';

        if ( ! empty( $item->classes ) ) {
            if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
                $cls .= ' active';
                $attrs['aria-current'] = 'page';
            } elseif ( array_intersect( array( 'current-page-ancestor', 'current-menu-ancestor' ), (array) $item->classes ) ) {
                $cls .= ' active';
                /* Ancestor: active highlight but no aria-current (not the exact page) */
            }
        }
        $attrs['class'] = $cls;
    }
    return $attrs;
}
add_filter( 'nav_menu_link_attributes', 'slavoj_bootstrap_nav_link_attrs', 10, 4 );

// =====================================================================
// CUSTOM POST TYPES
// =====================================================================

function slavoj_register_post_types() {

    // --- ZÁPASY ---
    register_post_type('zapas', array(
        'labels' => array(
            'name'               => 'Zápasy',
            'singular_name'      => 'Zápas',
            'add_new'            => 'Přidat zápas',
            'add_new_item'       => 'Přidat nový zápas',
            'edit_item'          => 'Upravit zápas',
            'new_item'           => 'Nový zápas',
            'view_item'          => 'Zobrazit zápas',
            'search_items'       => 'Hledat zápasy',
            'not_found'          => 'Žádné zápasy nenalezeny',
            'not_found_in_trash' => 'Žádné zápasy v koši',
            'all_items'          => 'Všechny zápasy',
            'menu_name'          => 'Zápasy',
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'zapasy'),
        'supports'            => array('title', 'thumbnail'),
        'menu_icon'           => 'dashicons-awards',
        'show_in_rest'        => true,
        'taxonomies'          => array('sezona', 'kategorie-tymu', 'stav-zapasu'),
        'capability_type'     => array('zapas', 'zapasy'),
        'map_meta_cap'        => true,
    ));

    // --- TÝMY ---
    register_post_type('tym', array(
        'labels' => array(
            'name'               => 'Týmy',
            'singular_name'      => 'Tým',
            'add_new'            => 'Přidat tým',
            'add_new_item'       => 'Přidat nový tým',
            'edit_item'          => 'Upravit tým',
            'new_item'           => 'Nový tým',
            'view_item'          => 'Zobrazit tým',
            'search_items'       => 'Hledat týmy',
            'not_found'          => 'Žádné týmy nenalezeny',
            'not_found_in_trash' => 'Žádné týmy v koši',
            'all_items'          => 'Všechny týmy',
            'menu_name'          => 'Týmy',
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'tymy'),
        'supports'            => array('title', 'editor', 'thumbnail'),
        'menu_icon'           => 'dashicons-groups',
        'show_in_rest'        => true,
        'taxonomies'          => array('sezona', 'kategorie-tymu'),
        'capability_type'     => array('tym', 'tymy'),
        'map_meta_cap'        => true,
    ));

    // --- HRÁČI ---
    register_post_type('hrac', array(
        'labels' => array(
            'name'               => 'Hráči',
            'singular_name'      => 'Hráč',
            'add_new'            => 'Přidat hráče',
            'add_new_item'       => 'Přidat nového hráče',
            'edit_item'          => 'Upravit hráče',
            'new_item'           => 'Nový hráč',
            'all_items'          => 'Všichni hráči',
            'menu_name'          => 'Hráči',
        ),
        'public'              => true,
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'hraci'),
        'supports'            => array('title'),
        'menu_icon'           => 'dashicons-id',
        'show_in_rest'        => true,
        'taxonomies'          => array('sezona', 'kategorie-tymu', 'pozice-hrace'),
        'capability_type'     => array('hrac', 'hraci'),
        'map_meta_cap'        => true,
    ));

    // --- GALERIE ---
    register_post_type('galerie', array(
        'labels' => array(
            'name'               => 'Galerie',
            'singular_name'      => 'Fotoalbum',
            'add_new'            => 'Přidat album',
            'add_new_item'       => 'Přidat nové album',
            'edit_item'          => 'Upravit album',
            'new_item'           => 'Nové album',
            'all_items'          => 'Všechna alba',
            'menu_name'          => 'Galerie',
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'galerie'),
        'supports'            => array('title', 'editor', 'thumbnail'),
        'menu_icon'           => 'dashicons-format-gallery',
        'show_in_rest'        => true,
        'taxonomies'          => array('sezona', 'kategorie-tymu'),
        'capability_type'     => array('album', 'alba'),
        'map_meta_cap'        => true,
    ));

    // --- SPONZOŘI ---
    register_post_type('sponzor', array(
        'labels' => array(
            'name'               => 'Sponzoři',
            'singular_name'      => 'Sponzor',
            'add_new'            => 'Přidat sponzora',
            'add_new_item'       => 'Přidat nového sponzora',
            'edit_item'          => 'Upravit sponzora',
            'all_items'          => 'Všichni sponzoři',
            'menu_name'          => 'Sponzoři',
        ),
        'public'              => true,
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'sponzori'),
        'supports'            => array('title', 'thumbnail'),
        'menu_icon'           => 'dashicons-star-filled',
        'show_in_rest'        => true,
        'capability_type'     => array('sponzor', 'sponzori'),
        'map_meta_cap'        => true,
    ));

    // --- KONTAKTY ---
    register_post_type('kontakt', array(
        'labels' => array(
            'name'               => 'Kontakty',
            'singular_name'      => 'Kontakt',
            'add_new'            => 'Přidat kontakt',
            'add_new_item'       => 'Přidat nový kontakt',
            'edit_item'          => 'Upravit kontakt',
            'all_items'          => 'Všechny kontakty',
            'menu_name'          => 'Kontakty',
        ),
        'public'              => true,
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'kontakty'),
        'supports'            => array('title', 'thumbnail'),
        'menu_icon'           => 'dashicons-phone',
        'show_in_rest'        => true,
        'capability_type'     => array('kontakt', 'kontakty'),
        'map_meta_cap'        => true,
    ));
}
add_action('init', 'slavoj_register_post_types');

// =====================================================================
// TAXONOMIE
// =====================================================================

function slavoj_register_taxonomies() {

    // Sezóna (sdílená pro Zápasy, Týmy, Hráče, Galerie)
    register_taxonomy('sezona', array('zapas', 'tym', 'hrac', 'galerie'), array(
        'labels' => array(
            'name'          => 'Sezóny',
            'singular_name' => 'Sezóna',
            'add_new_item'  => 'Přidat sezónu',
            'edit_item'     => 'Upravit sezónu',
            'all_items'     => 'Všechny sezóny',
            'menu_name'     => 'Sezóny',
        ),
        'hierarchical'      => false,
        'public'            => true,
        'rewrite'           => array('slug' => 'sezona'),
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ));

    // Kategorie týmu (Muži A, Muži B, Dorost, …)
    register_taxonomy('kategorie-tymu', array('zapas', 'tym', 'hrac', 'galerie'), array(
        'labels' => array(
            'name'          => 'Kategorie týmu',
            'singular_name' => 'Kategorie týmu',
            'add_new_item'  => 'Přidat kategorii',
            'edit_item'     => 'Upravit kategorii',
            'all_items'     => 'Všechny kategorie',
            'menu_name'     => 'Kategorie týmu',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'rewrite'           => array('slug' => 'kategorie-tymu'),
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ));

    // Stav zápasu (Nadcházející, Odehraný, Zrušený)
    register_taxonomy('stav-zapasu', array('zapas'), array(
        'labels' => array(
            'name'          => 'Stav zápasu',
            'singular_name' => 'Stav zápasu',
            'add_new_item'  => 'Přidat stav',
            'edit_item'     => 'Upravit stav',
            'all_items'     => 'Všechny stavy',
            'menu_name'     => 'Stav zápasu',
        ),
        'hierarchical'      => false,
        'public'            => true,
        'rewrite'           => array('slug' => 'stav-zapasu'),
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ));

    // Pozice hráče (Brankář, Obránce, Záložník, Útočník)
    register_taxonomy('pozice-hrace', array('hrac'), array(
        'labels' => array(
            'name'          => 'Pozice hráče',
            'singular_name' => 'Pozice',
            'add_new_item'  => 'Přidat pozici',
            'edit_item'     => 'Upravit pozici',
            'all_items'     => 'Všechny pozice',
            'menu_name'     => 'Pozice',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'rewrite'           => array('slug' => 'pozice-hrace'),
        'show_admin_column' => true,
        'show_in_rest'      => true,
    ));
}
add_action('init', 'slavoj_register_taxonomies');

// =====================================================================
// META BOXY – ZÁPAS
// =====================================================================

function slavoj_zapas_meta_boxes() {
    add_meta_box(
        'slavoj_zapas_detail',
        'Detail zápasu',
        'slavoj_zapas_meta_box_html',
        'zapas',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_zapas_meta_boxes');

function slavoj_zapas_meta_box_html($post) {
    wp_nonce_field('slavoj_zapas_nonce', 'slavoj_zapas_nonce_field');
    $datum   = get_post_meta($post->ID, 'datum_zapasu', true);
    $cas     = get_post_meta($post->ID, 'cas_zapasu', true);
    $domaci  = get_post_meta($post->ID, 'domaci', true);
    $hoste   = get_post_meta($post->ID, 'hoste', true);
    $skore   = get_post_meta($post->ID, 'skore', true);
    $strelci = get_post_meta($post->ID, 'strelci', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="datum_zapasu">Datum zápasu</label></th>
        <td><input type="date" id="datum_zapasu" name="datum_zapasu" value="<?php echo esc_attr($datum); ?>" class="widefat"></td>
      </tr>
      <tr>
        <th><label for="cas_zapasu">Čas výkopu</label></th>
        <td><input type="time" id="cas_zapasu" name="cas_zapasu" value="<?php echo esc_attr($cas); ?>" class="widefat"></td>
      </tr>
      <tr>
        <th><label for="domaci">Domácí tým</label></th>
        <td><input type="text" id="domaci" name="domaci" value="<?php echo esc_attr($domaci); ?>" class="widefat" placeholder="např. TJ Slavoj Mýto"></td>
      </tr>
      <tr>
        <th><label for="hoste">Hostující tým</label></th>
        <td><input type="text" id="hoste" name="hoste" value="<?php echo esc_attr($hoste); ?>" class="widefat" placeholder="např. FK Rapid Plzeň"></td>
      </tr>
      <tr>
        <th><label for="skore">Skóre</label></th>
        <td><input type="text" id="skore" name="skore" value="<?php echo esc_attr($skore); ?>" class="widefat" placeholder="např. 3:1 (prázdné = zápas nebyl odehrán)"></td>
      </tr>
      <tr>
        <th><label for="strelci">Střelci</label></th>
        <td><input type="text" id="strelci" name="strelci" value="<?php echo esc_attr($strelci); ?>" class="widefat" placeholder="např. 2× Novák, Bejček"></td>
      </tr>
    </table>
    <?php
}

function slavoj_zapas_save_meta($post_id) {
    if (!isset($_POST['slavoj_zapas_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_zapas_nonce_field'])), 'slavoj_zapas_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('datum_zapasu', 'cas_zapasu', 'domaci', 'hoste', 'skore', 'strelci');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }
}
add_action('save_post_zapas', 'slavoj_zapas_save_meta');

// =====================================================================
// META BOXY – TÝM
// =====================================================================

function slavoj_tym_meta_boxes() {
    add_meta_box(
        'slavoj_tym_detail',
        'Detail týmu',
        'slavoj_tym_meta_box_html',
        'tym',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_tym_meta_boxes');

function slavoj_tym_meta_box_html($post) {
    wp_nonce_field('slavoj_tym_nonce', 'slavoj_tym_nonce_field');
    $pocet_hracu    = slavoj_count_hracu_tymu($post->ID);
    $hlavni_trener  = get_post_meta($post->ID, 'hlavni_trener', true);
    $asistent       = get_post_meta($post->ID, 'asistent_trenera', true);
    $zdravotnik     = get_post_meta($post->ID, 'zdravotnik', true);
    $tym_slug       = get_post_meta($post->ID, 'tym_slug', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="tym_slug">Identifikátor týmu (slug)</label></th>
        <td><input type="text" id="tym_slug" name="tym_slug" value="<?php echo esc_attr($tym_slug); ?>" class="widefat" placeholder="např. muzi-a"></td>
      </tr>
      <tr>
        <th>Počet hráčů</th>
        <td>
          <strong><?php echo esc_html($pocet_hracu); ?></strong>
          <p class="description">Vypočítáno automaticky z hráčů přiřazených k tomuto týmu v dané sezóně.</p>
        </td>
      </tr>
      <tr>
        <th><label for="hlavni_trener">Hlavní trenér</label></th>
        <td><input type="text" id="hlavni_trener" name="hlavni_trener" value="<?php echo esc_attr($hlavni_trener); ?>" class="widefat"></td>
      </tr>
      <tr>
        <th><label for="asistent_trenera">Asistent trenéra</label></th>
        <td><input type="text" id="asistent_trenera" name="asistent_trenera" value="<?php echo esc_attr($asistent); ?>" class="widefat"></td>
      </tr>
      <tr>
        <th><label for="zdravotnik">Zdravotník</label></th>
        <td><input type="text" id="zdravotnik" name="zdravotnik" value="<?php echo esc_attr($zdravotnik); ?>" class="widefat"></td>
      </tr>
    </table>
    <?php
}

/**
 * Vypočítá a uloží počet hráčů pro daný tým na základě aktuálního stavu hráčů CPT.
 * Hráči jsou filtrováni podle tym_slug týmu a sezóny týmu.
 * Pokud tým nemá přiřazenou sezónu, použije se aktuálně nejnovější sezóna.
 *
 * @param int $post_id  ID záznamu CPT tym
 * @return int  Počet nalezených hráčů
 */
function slavoj_count_hracu_tymu($post_id) {
    $tym_slug = get_post_meta($post_id, 'tym_slug', true);
    if (!$tym_slug) {
        update_post_meta($post_id, 'pocet_hracu', 0);
        return 0;
    }

    // Zjistíme sezónu týmu; pokud není určena, použijeme nejnovější dostupnou.
    $sez_terms = get_the_terms($post_id, 'sezona');
    if ($sez_terms && !is_wp_error($sez_terms)) {
        $sezona_slug = $sez_terms[0]->slug;
    } else {
        $all_sezony = get_terms(array('taxonomy' => 'sezona', 'hide_empty' => false, 'orderby' => 'name', 'order' => 'DESC'));
        $sezona_slug = (!is_wp_error($all_sezony) && !empty($all_sezony)) ? $all_sezony[0]->slug : '';
    }

    $args = array(
        'post_type'      => 'hrac',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => 'tym_slug',
                'value'   => $tym_slug,
                'compare' => '=',
            ),
        ),
    );

    if ($sezona_slug) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'sezona',
                'field'    => 'slug',
                'terms'    => $sezona_slug,
            ),
        );
    }

    $query = new WP_Query($args);
    $count = $query->found_posts;
    wp_reset_postdata();

    update_post_meta($post_id, 'pocet_hracu', $count);
    return $count;
}

function slavoj_tym_save_meta($post_id) {
    if (!isset($_POST['slavoj_tym_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_tym_nonce_field'])), 'slavoj_tym_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('tym_slug', 'hlavni_trener', 'asistent_trenera', 'zdravotnik');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }

    // Přepočítáme počet hráčů po uložení týmu.
    slavoj_count_hracu_tymu($post_id);
}
add_action('save_post_tym', 'slavoj_tym_save_meta');

/**
 * Po uložení hráče aktualizujeme pocet_hracu nadřízeného týmu.
 *
 * @param int $hrac_id  ID záznamu CPT hrac
 */
function slavoj_update_pocet_hracu_po_zmene_hrace($hrac_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    slavoj_recalculate_tym_pocet_hracu($hrac_id);
}
add_action('save_post_hrac', 'slavoj_update_pocet_hracu_po_zmene_hrace');

/**
 * Před smazáním hráče aktualizujeme pocet_hracu nadřízeného týmu.
 *
 * @param int $hrac_id  ID záznamu CPT hrac
 */
function slavoj_update_pocet_hracu_pred_smazanim_hrace($hrac_id) {
    if (get_post_type($hrac_id) !== 'hrac') return;
    slavoj_recalculate_tym_pocet_hracu($hrac_id);
}
add_action('before_delete_post', 'slavoj_update_pocet_hracu_pred_smazanim_hrace');

/**
 * Pomocná funkce – najde tým podle tym_slug hráče a přepočítá pocet_hracu.
 *
 * @param int $hrac_id  ID záznamu CPT hrac
 */
function slavoj_recalculate_tym_pocet_hracu($hrac_id) {
    $tym_slug = get_post_meta($hrac_id, 'tym_slug', true);
    if (!$tym_slug) return;

    $tymy = get_posts(array(
        'post_type'      => 'tym',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => 'tym_slug',
                'value'   => $tym_slug,
                'compare' => '=',
            ),
        ),
    ));

    foreach ($tymy as $tym_id) {
        slavoj_count_hracu_tymu($tym_id);
    }
}

// =====================================================================
// META BOXY – HRÁČ
// =====================================================================

function slavoj_hrac_meta_boxes() {
    add_meta_box(
        'slavoj_hrac_detail',
        'Detail hráče',
        'slavoj_hrac_meta_box_html',
        'hrac',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_hrac_meta_boxes');

function slavoj_hrac_meta_box_html($post) {
    wp_nonce_field('slavoj_hrac_nonce', 'slavoj_hrac_nonce_field');
    $cislo        = get_post_meta($post->ID, 'cislo', true);
    $rok_narozeni = get_post_meta($post->ID, 'rok_narozeni', true);
    $tym_slug     = get_post_meta($post->ID, 'tym_slug', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="cislo">Číslo dresu</label></th>
        <td><input type="number" id="cislo" name="cislo" value="<?php echo esc_attr($cislo); ?>" class="widefat" min="1" max="99"></td>
      </tr>
      <tr>
        <th><label for="rok_narozeni">Rok narození</label></th>
        <td><input type="number" id="rok_narozeni" name="rok_narozeni" value="<?php echo esc_attr($rok_narozeni); ?>" class="widefat" min="1950" max="2020" placeholder="např. 1995"></td>
      </tr>
      <tr>
        <th><label for="tym_slug">Slug týmu</label></th>
        <td><input type="text" id="tym_slug" name="tym_slug" value="<?php echo esc_attr($tym_slug); ?>" class="widefat" placeholder="např. muzi-a"></td>
      </tr>
    </table>
    <?php
}

function slavoj_hrac_save_meta($post_id) {
    if (!isset($_POST['slavoj_hrac_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_hrac_nonce_field'])), 'slavoj_hrac_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('cislo', 'rok_narozeni', 'tym_slug');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }
}
add_action('save_post_hrac', 'slavoj_hrac_save_meta');

// =====================================================================
// META BOXY – GALERIE
// =====================================================================

function slavoj_galerie_meta_boxes() {
    add_meta_box(
        'slavoj_galerie_detail',
        'Detail alba',
        'slavoj_galerie_meta_box_html',
        'galerie',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_galerie_meta_boxes');

function slavoj_galerie_meta_box_html($post) {
    wp_nonce_field('slavoj_galerie_nonce', 'slavoj_galerie_nonce_field');
    $sezona  = get_post_meta($post->ID, 'sezona', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="galerie_sezona">Sezóna</label></th>
        <td><input type="text" id="galerie_sezona" name="sezona" value="<?php echo esc_attr($sezona); ?>" class="widefat" placeholder="např. 2025/26"></td>
      </tr>
    </table>
    <p class="description">Kategorie týmu přiřaďte pomocí panelu <strong>Kategorie týmu</strong> vpravo. Fotografie přidejte přes funkci <strong>Obrázek příspěvku</strong> (náhled alba) nebo vložte galerii přímo do obsahu příspěvku.</p>
    <?php
}

function slavoj_galerie_save_meta($post_id) {
    if (!isset($_POST['slavoj_galerie_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_galerie_nonce_field'])), 'slavoj_galerie_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('sezona');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }
}
add_action('save_post_galerie', 'slavoj_galerie_save_meta');

// =====================================================================
// META BOXY – SPONZOR
// =====================================================================

function slavoj_sponzor_meta_boxes() {
    add_meta_box(
        'slavoj_sponzor_detail',
        'Detail sponzora',
        'slavoj_sponzor_meta_box_html',
        'sponzor',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_sponzor_meta_boxes');

function slavoj_sponzor_meta_box_html($post) {
    wp_nonce_field('slavoj_sponzor_nonce', 'slavoj_sponzor_nonce_field');
    $web = get_post_meta($post->ID, 'web_sponzora', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="web_sponzora">Webové stránky</label></th>
        <td><input type="url" id="web_sponzora" name="web_sponzora" value="<?php echo esc_attr($web); ?>" class="widefat" placeholder="https://"></td>
      </tr>
    </table>
    <?php
}

function slavoj_sponzor_save_meta($post_id) {
    if (!isset($_POST['slavoj_sponzor_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_sponzor_nonce_field'])), 'slavoj_sponzor_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['web_sponzora'])) {
        update_post_meta($post_id, 'web_sponzora', sanitize_text_field(wp_unslash($_POST['web_sponzora'])));
    }
}
add_action('save_post_sponzor', 'slavoj_sponzor_save_meta');

// =====================================================================
// META BOXY – KONTAKT
// =====================================================================

function slavoj_kontakt_meta_boxes() {
    add_meta_box(
        'slavoj_kontakt_detail',
        'Detail kontaktu',
        'slavoj_kontakt_meta_box_html',
        'kontakt',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'slavoj_kontakt_meta_boxes');

function slavoj_kontakt_meta_box_html($post) {
    wp_nonce_field('slavoj_kontakt_nonce', 'slavoj_kontakt_nonce_field');
    $pozice  = get_post_meta($post->ID, 'pozice', true);
    $telefon = get_post_meta($post->ID, 'telefon', true);
    $email   = get_post_meta($post->ID, 'email', true);
    $poradi  = get_post_meta($post->ID, 'poradi', true);
    ?>
    <table class="form-table">
      <tr>
        <th><label for="pozice">Funkce / Pozice</label></th>
        <td><input type="text" id="pozice" name="pozice" value="<?php echo esc_attr($pozice); ?>" class="widefat" placeholder="např. Předseda klubu"></td>
      </tr>
      <tr>
        <th><label for="telefon">Telefon</label></th>
        <td><input type="text" id="telefon" name="telefon" value="<?php echo esc_attr($telefon); ?>" class="widefat" placeholder="např. +420 777 000 000"></td>
      </tr>
      <tr>
        <th><label for="email">E-mail</label></th>
        <td><input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" class="widefat"></td>
      </tr>
      <tr>
        <th><label for="poradi">Pořadí zobrazení</label></th>
        <td><input type="number" id="poradi" name="poradi" value="<?php echo esc_attr($poradi); ?>" class="widefat" min="0"></td>
      </tr>
    </table>
    <?php
}

function slavoj_kontakt_save_meta($post_id) {
    if (!isset($_POST['slavoj_kontakt_nonce_field'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['slavoj_kontakt_nonce_field'])), 'slavoj_kontakt_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('pozice', 'telefon', 'email', 'poradi');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = ($field === 'email')
                ? sanitize_email(wp_unslash($_POST[$field]))
                : sanitize_text_field(wp_unslash($_POST[$field]));
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_kontakt', 'slavoj_kontakt_save_meta');

// =====================================================================
// POMOCNÉ FUNKCE
// =====================================================================

/**
 * Zjistí, zda je název týmu vlastním klubem TJ Slavoj Mýto.
 *
 * @param string $nazev_tymu  Název týmu z meta pole domaci nebo hoste.
 * @return bool
 */
function slavoj_is_club_team($nazev_tymu) {
    return stripos($nazev_tymu, 'Slavoj') !== false
        || stripos($nazev_tymu, 'TJ Mýto') !== false;
}

/**
 * Vrátí slug nejnovější sezóny (podle term_id – nejvyšší = naposledy přidaná).
 * Používá se jako výchozí hodnota filtru sezóny.
 *
 * @return string  Slug sezóny (např. '2025-26'), nebo '' pokud žádná neexistuje.
 */
function slavoj_get_latest_sezona_slug() {
    $terms = get_terms(array(
        'taxonomy'   => 'sezona',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'DESC',
        'number'     => 1,
    ));
    return (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->slug : '';
}

/**
 * Vrátí kanonické pořadí kategorií týmů (slug => název) od nejvyšší ligy.
 * Používá se pro řazení filtrů, dropdown seznamů i karet na homepage.
 *
 * @return array  Asociativní pole slug => název v požadovaném pořadí.
 */
function slavoj_kategorie_poradi() {
    return array(
        'muzi-a'            => 'Muži A',
        'muzi-b'            => 'Muži B',
        'stara-garda'       => 'Stará garda',
        'dorost'            => 'Dorost',
        'starsi-zaci'       => 'Starší žáci',
        'mladsi-zaci'       => 'Mladší žáci',
        'starsi-pripravka'  => 'Starší přípravka',
        'mladsi-pripravka'  => 'Mladší přípravka',
        'mini-pripravka'    => 'Mini přípravka',
    );
}

/**
 * Seřadí pole WP_Term objektů (taxonomie kategorie-tymu) dle kanonického pořadí.
 *
 * @param array $terms  Pole WP_Term objektů.
 * @return array  Seřazené pole.
 */
function slavoj_sort_tymy($terms) {
    if (empty($terms) || is_wp_error($terms)) return $terms;
    $poradi = array_keys(slavoj_kategorie_poradi());
    usort($terms, function($a, $b) use ($poradi) {
        $ia = array_search($a->slug, $poradi);
        $ib = array_search($b->slug, $poradi);
        $ia = ($ia === false) ? 999 : $ia;
        $ib = ($ib === false) ? 999 : $ib;
        return $ia - $ib;
    });
    return $terms;
}

/**
 * Vrátí zobrazovaný název kategorie týmu podle jeho slugu.
 *
 * @param string $slug  Slug taxonomie kategorie-tymu (např. 'muzi-a').
 * @return string  Zobrazovaný název (např. 'Muži A'), nebo samotný slug pokud není nalezen.
 */
function slavoj_get_team_display_name($slug) {
    $mapa = slavoj_kategorie_poradi();
    return isset($mapa[$slug]) ? $mapa[$slug] : $slug;
}

/**
 * Vrátí výsledek zápasu z pohledu TJ Slavoj Mýto.
 *
 * @param string $domaci  Název domácího týmu.
 * @param string $hoste   Název hostujícího týmu.
 * @param string $skore   Skóre ve formátu "domácí:hosté", např. "3:1". Prázdný řetězec = nadcházející.
 * @return string  'vyhral' | 'prohral' | 'remiza' | '' (nezapas/nadcházející)
 */
function slavoj_zapas_vysledek($domaci, $hoste, $skore) {
    if (empty($skore) || !preg_match('/^(\d+):(\d+)$/', $skore, $m)) {
        return '';
    }
    $goly_domaci = (int) $m[1];
    $goly_hoste  = (int) $m[2];

    $je_domaci = slavoj_is_club_team($domaci);
    $je_hoste  = slavoj_is_club_team($hoste);

    if (!$je_domaci && !$je_hoste) {
        return '';
    }

    $nase_goly   = $je_domaci ? $goly_domaci : $goly_hoste;
    $jejich_goly = $je_domaci ? $goly_hoste  : $goly_domaci;

    if ($nase_goly > $jejich_goly) {
        return 'vyhral';
    }
    if ($nase_goly < $jejich_goly) {
        return 'prohral';
    }
    return 'remiza';
}

/**
 * Záložní menu pokud není nastaveno v administraci
 */
function slavoj_fallback_menu() {
    echo '<ul class="nav__list">';
    $polozky = array(
        'Domů'     => home_url('/'),
        'Zápasy'   => home_url('/zapasy/'),
        'Týmy'     => home_url('/tymy/'),
        'Galerie'  => home_url('/galerie/'),
        'Historie' => home_url('/historie/'),
        'Sponzoři' => home_url('/sponzori/'),
        'Kontakty' => home_url('/kontakty/'),
    );
    foreach ($polozky as $label => $url) {
        echo '<li class="nav__item"><a class="nav__link" href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Záložní menu pro Bootstrap navbar (primary) – pokud není menu v administraci.
 */
function slavoj_fallback_primary_menu() {
    $polozky = array(
        'Domů'     => home_url('/'),
        'Zápasy'   => home_url('/zapasy/'),
        'Týmy'     => home_url('/tymy/'),
        'Galerie'  => home_url('/galerie/'),
        'Historie' => home_url('/historie/'),
        'Sponzoři' => home_url('/sponzori/'),
        'Kontakty' => home_url('/kontakty/'),
    );
    echo '<ul class="navbar-nav ms-auto">';
    foreach ($polozky as $label => $url) {
        echo '<li class="nav-item"><a class="nav-link" href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Záložní menu pro patičku – pokud není menu v administraci.
 */
function slavoj_fallback_footer_menu() {
    $polozky = array(
        'Zápasy'   => home_url('/zapasy/'),
        'Týmy'     => home_url('/tymy/'),
        'Galerie'  => home_url('/galerie/'),
        'Kontakty' => home_url('/kontakty/'),
        'Sponzoři' => home_url('/sponzori/'),
    );
    echo '<ul class="footer-nav__list">';
    foreach ($polozky as $label => $url) {
        echo '<li><a class="footer-nav__link" href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

/**
 * Nav Walker – přidá třídy nav__item a nav__link na položky wp_nav_menu().
 */
class Slavoj_Nav_Walker extends Walker_Nav_Menu {
    /**
     * @param string   $output Running output HTML string.
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of the current menu item.
     * @param stdClass $args   wp_nav_menu() arguments object.
     * @param int      $id     ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes   = empty($item->classes) ? array() : (array) $item->classes;
        $is_active = in_array('current-menu-item', $classes, true);

        $li_classes = 'nav__item';
        if ($is_active) {
            $li_classes .= ' nav__item--active';
        }

        $output .= '<li class="' . esc_attr($li_classes) . '">';

        $link_classes = 'nav__link';
        if ($is_active) {
            $link_classes .= ' nav__link--active';
        }

        $atts          = array();
        $atts['href']  = !empty($item->url) ? $item->url : '';
        $atts['class'] = $link_classes;
        if ($item->target) {
            $atts['target'] = $item->target;
        }
        if ($item->xfn) {
            $atts['rel'] = $item->xfn;
        }

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
            }
        }

        $title  = apply_filters('the_title', $item->title, $item->ID);
        $output .= '<a' . $attributes . '>' . esc_html($title) . '</a>';
    }
}

/**
 * Výpis soupisky hráčů podle kategorie (podpora pro stávající kód i CPT hrac)
 * @param string $kategorie  slug taxonomie kategorie-tymu nebo tym_slug meta hodnota
 */
function slavoj_vypis_soupisku($kategorie) {
    // Zkusíme nejdříve CPT 'hrac'
    $args = array(
        'post_type'      => 'hrac',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'cislo',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => 'tym_slug',
                'value'   => $kategorie,
                'compare' => '=',
            ),
        ),
    );

    $query = new WP_Query($args);

    // Záložně zkusíme starý přístup přes category
    if (!$query->have_posts()) {
        $args = array(
            'category_name'  => $kategorie,
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'cislo',
            'order'          => 'ASC',
        );
        $query = new WP_Query($args);
    }

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

/**
 * Pomocná funkce pro výpis hráčů daného týmu seskupených podle pozice
 * @param string $tym_slug  slug týmu (meta hodnota)
 */
function slavoj_vypis_hrace_tymu($tym_slug) {
    $pozice_skupiny = array(
        'brankari'    => 'Brankáři',
        'hraci-v-poli' => 'Hráči v poli',
    );

    foreach ($pozice_skupiny as $pozice_slug => $pozice_nazev) {
        $args = array(
            'post_type'      => 'hrac',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'cislo',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'tym_slug',
                    'value'   => $tym_slug,
                    'compare' => '=',
                ),
            ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'pozice-hrace',
                    'field'    => 'slug',
                    'terms'    => $pozice_slug,
                ),
            ),
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            echo '<p class="fw-semibold mb-2">' . esc_html($pozice_nazev) . '</p>';
            echo '<div class="row mb-3">';
            while ($query->have_posts()) :
                $query->the_post();
                ?>
                <div class="col-6">
                    <strong><?php echo esc_html(get_post_meta(get_the_ID(), 'cislo', true)); ?></strong>
                    <?php the_title(); ?>
                    &nbsp;&nbsp; nar.:
                    <?php echo esc_html(get_post_meta(get_the_ID(), 'rok_narozeni', true)); ?>
                </div>
                <?php
            endwhile;
            echo '</div>';
        endif;
        wp_reset_postdata();
    }
}

/**
 * Lazy loading pro obrázky – přidání atributu loading="lazy"
 */
function slavoj_add_lazy_loading($content) {
    return str_replace('<img ', '<img loading="lazy" ', $content);
}
add_filter('the_content', 'slavoj_add_lazy_loading');
add_filter('post_thumbnail_html', 'slavoj_add_lazy_loading');

// =====================================================================
// FRONTEND AKCE – ULOŽENÍ SKÓRE A STŘELCŮ (admin-post.php handler)
// =====================================================================

/**
 * Zpracuje formulář pro zápis skóre a střelců z frontendu (single-zapas.php).
 * Dostupné pouze pro přihlášené uživatele s oprávněním edit_post.
 */
function slavoj_handle_update_zapas() {
    if (!isset($_POST['slavoj_zapas_update_nonce'])) {
        wp_die('Neplatný požadavek.', 403);
    }

    $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;

    if (!$post_id || !wp_verify_nonce(
        sanitize_text_field(wp_unslash($_POST['slavoj_zapas_update_nonce'])),
        'slavoj_update_zapas_' . $post_id
    )) {
        wp_die('Neplatný bezpečnostní token.', 403);
    }

    if (!current_user_can('edit_post', $post_id)) {
        wp_die('Nemáte oprávnění upravovat tento příspěvek.', 403);
    }

    $skore   = isset($_POST['skore'])   ? sanitize_text_field(wp_unslash($_POST['skore']))   : '';
    $strelci = isset($_POST['strelci']) ? sanitize_text_field(wp_unslash($_POST['strelci'])) : '';

    update_post_meta($post_id, 'skore', $skore);
    update_post_meta($post_id, 'strelci', $strelci);

    wp_redirect(add_query_arg('slavoj_saved', '1', get_permalink($post_id)));
    exit;
}
add_action('admin_post_slavoj_update_zapas', 'slavoj_handle_update_zapas');

// Nepřihlášení uživatelé nemohou odeslat formulář
add_action('admin_post_nopriv_slavoj_update_zapas', function () {
    wp_die('Pro tuto akci se musíte přihlásit.', 403);
});

// =====================================================================
// CUSTOMIZER – KONTAKTNÍ INFORMACE A NASTAVENÍ WEBU
// =====================================================================

/**
 * Registrace nastavení Customizeru: adresa klubu, e-mail a URL mapy.
 * Editovatelné přes Vzhled → Přizpůsobit v administraci WordPress.
 */
function tjsm_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'tjsm_kontakt', array(
        'title'    => 'Kontaktní informace klubu',
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'tjsm_adresa', array(
        'default'           => 'Mýto 27, 338 05 Mýto',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'tjsm_adresa', array(
        'label'   => 'Adresa klubu',
        'section' => 'tjsm_kontakt',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'tjsm_email', array(
        'default'           => 'tjslavojmyto@seznam.cz',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'tjsm_email', array(
        'label'   => 'E-mail klubu',
        'section' => 'tjsm_kontakt',
        'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'tjsm_mapa_url', array(
        'default'           => TJSM_DEFAULT_MAP_URL,
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'tjsm_mapa_url', array(
        'label'   => 'URL embedu mapy (OpenStreetMap, Google Maps apod.)',
        'section' => 'tjsm_kontakt',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'tjsm_customize_register' );

// =====================================================================
// INICIALIZACE STRÁNKY – ADMIN NÁSTROJ
// =====================================================================

/**
 * Registrace admin stránky pro inicializaci webu TJ Slavoj Mýto.
 * Dostupná pod Nástroje → Inicializace webu.
 */
function tjsm_init_admin_menu() {
    add_management_page(
        'Inicializace webu TJ Slavoj Mýto',
        'Inicializace webu',
        'manage_options',
        'tjsm-inicializace',
        'tjsm_init_admin_page'
    );
}
add_action( 'admin_menu', 'tjsm_init_admin_menu' );

/**
 * Zpracuje formulář inicializace a vrátí pole zpráv o výsledcích.
 *
 * @return string[]
 */
function tjsm_init_handle_form() {
    if (
        ! isset( $_POST['tjsm_init_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tjsm_init_nonce'] ) ), 'tjsm_init_action' ) ||
        ! current_user_can( 'manage_options' )
    ) {
        return array();
    }

    $log = array();

    // 1. Stránky (Pages) s přiřazením šablony
    $pages = array(
        array( 'title' => 'Úvod',     'slug' => 'uvod',      'template' => '' ),
        array( 'title' => 'Aktuality','slug' => 'aktuality', 'template' => 'page-aktuality.php' ),
        array( 'title' => 'Zápasy',   'slug' => 'zapasy',    'template' => '' ), // URL obsluhuje CPT archive (archive-zapas.php)
        array( 'title' => 'Týmy',     'slug' => 'tymy',      'template' => '' ), // URL obsluhuje CPT archive (archive-tym.php)
        array( 'title' => 'Galerie',  'slug' => 'galerie',   'template' => '' ), // URL obsluhuje CPT archive (archive-galerie.php)
        array( 'title' => 'Historie', 'slug' => 'historie',  'template' => 'page-historie.php' ),
        array( 'title' => 'Sponzoři', 'slug' => 'sponzori',  'template' => 'page-sponzori.php' ),
        array( 'title' => 'Kontakty', 'slug' => 'kontakty',  'template' => 'page-kontakty.php' ),
    );

    foreach ( $pages as $page_data ) {
        $exists = get_page_by_path( $page_data['slug'] );
        if ( $exists ) {
            $log[] = '✓ Stránka „' . $page_data['title'] . '" již existuje (přeskočena).';
            continue;
        }
        $page_id = wp_insert_post( array(
            'post_title'   => $page_data['title'],
            'post_name'    => $page_data['slug'],
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => '',
        ) );
        if ( is_wp_error( $page_id ) ) {
            $log[] = '✗ Chyba při vytváření stránky „' . $page_data['title'] . '": ' . $page_id->get_error_message();
        } else {
            if ( $page_data['template'] ) {
                update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
            }
            $log[] = '+ Stránka „' . $page_data['title'] . '" vytvořena (ID: ' . $page_id . ').';
        }
    }

    // 2. Nastavení úvodní stránky
    $front_page = get_page_by_path( 'uvod' );
    if ( $front_page && (int) get_option( 'page_on_front' ) !== $front_page->ID ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page->ID );
        $log[] = '+ Úvodní stránka webu nastavena na „Úvod".';
    }

    // 3. Kategorie příspěvků
    $categories = array(
        array( 'name' => 'Aktuality', 'slug' => 'aktuality', 'desc' => 'Aktuality a novinky klubu' ),
    );
    foreach ( $categories as $cat ) {
        if ( term_exists( $cat['slug'], 'category' ) ) {
            $log[] = '✓ Kategorie „' . $cat['name'] . '" již existuje (přeskočena).';
        } else {
            $result = wp_insert_term( $cat['name'], 'category', array(
                'slug'        => $cat['slug'],
                'description' => $cat['desc'],
            ) );
            if ( is_wp_error( $result ) ) {
                $log[] = '✗ Chyba při vytváření kategorie „' . $cat['name'] . '": ' . $result->get_error_message();
            } else {
                $log[] = '+ Kategorie „' . $cat['name'] . '" vytvořena.';
            }
        }
    }

    // 4. Termíny taxonomií (kategorie-tymu, stav-zapasu, sezona, pozice-hrace)
    $taxonomy_terms = array(
        'kategorie-tymu' => array(
            array( 'name' => 'Muži A',            'slug' => 'muzi-a' ),
            array( 'name' => 'Muži B',            'slug' => 'muzi-b' ),
            array( 'name' => 'Stará garda',       'slug' => 'stara-garda' ),
            array( 'name' => 'Dorost',             'slug' => 'dorost' ),
            array( 'name' => 'Starší žáci',       'slug' => 'starsi-zaci' ),
            array( 'name' => 'Mladší žáci',       'slug' => 'mladsi-zaci' ),
            array( 'name' => 'Starší přípravka',  'slug' => 'starsi-pripravka' ),
            array( 'name' => 'Mladší přípravka',  'slug' => 'mladsi-pripravka' ),
            array( 'name' => 'Mini přípravka',    'slug' => 'mini-pripravka' ),
        ),
        'stav-zapasu' => array(
            array( 'name' => 'Nadcházející', 'slug' => 'nadchazejici' ),
            array( 'name' => 'Odehraný',     'slug' => 'odehrany' ),
            array( 'name' => 'Zrušený',      'slug' => 'zruseny' ),
        ),
        'sezona' => array(
            array( 'name' => '2024/2025', 'slug' => '2024-2025' ),
            array( 'name' => '2025/2026', 'slug' => '2025-2026' ),
        ),
        'pozice-hrace' => array(
            array( 'name' => 'Brankáři',     'slug' => 'brankari' ),
            array( 'name' => 'Hráči v poli', 'slug' => 'hraci-v-poli' ),
        ),
    );

    foreach ( $taxonomy_terms as $taxonomy => $terms ) {
        foreach ( $terms as $term ) {
            if ( term_exists( $term['slug'], $taxonomy ) ) {
                $log[] = '✓ Termín „' . $term['name'] . '" (' . $taxonomy . ') již existuje (přeskočen).';
            } else {
                $result = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
                if ( is_wp_error( $result ) ) {
                    $log[] = '✗ Chyba při vytváření termínu „' . $term['name'] . '" (' . $taxonomy . '): ' . $result->get_error_message();
                } else {
                    $log[] = '+ Termín „' . $term['name'] . '" (' . $taxonomy . ') vytvořen.';
                }
            }
        }
    }

    return $log;
}

/**
 * HTML výstup admin stránky inicializace + importu dat.
 */
function tjsm_init_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Nemáte potřebná oprávnění.', '', array( 'response' => 403 ) );
    }

    $log_init   = array();
    $log_import = array();

    if ( isset( $_POST['tjsm_init_nonce'] ) ) {
        $log_init = tjsm_init_handle_form();
    }
    if ( isset( $_POST['tjsm_import_nonce'] ) ) {
        $log_import = tjsm_import_handle_form();
    }
    ?>
    <div class="wrap">
      <h1>🚀 Nástroje webu – TJ Slavoj Mýto</h1>

      <?php if ( ! empty( $log_init ) ) : ?>
        <div class="notice notice-success is-dismissible">
          <p><strong>Výsledky inicializace:</strong></p>
          <ul style="margin-left:1.5em;list-style:disc">
            <?php foreach ( $log_init as $row ) : ?>
              <li><?php echo esc_html( $row ); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ( ! empty( $log_import ) ) : ?>
        <div class="notice notice-success is-dismissible">
          <p><strong>Výsledky importu:</strong></p>
          <ul style="margin-left:1.5em;list-style:disc">
            <?php foreach ( $log_import as $row ) : ?>
              <li><?php echo esc_html( $row ); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- SEKCE 1: Inicializace -->
      <h2>1. Inicializace webu</h2>
      <p>
        Vytvoří všechny potřebné stránky, kategorie a termíny taxonomií pro správný chod webu.<br>
        Již existující záznamy budou přeskočeny – akci lze bezpečně spustit opakovaně.
      </p>

      <form method="post">
        <?php wp_nonce_field( 'tjsm_init_action', 'tjsm_init_nonce' ); ?>
        <?php submit_button( 'Spustit inicializaci webu', 'primary large' ); ?>
      </form>

      <details style="margin-top:.5em">
        <summary style="cursor:pointer;color:#2271b1">Co bude vytvořeno</summary>
        <table class="widefat striped" style="margin-top:.5em">
          <thead><tr><th>Typ</th><th>Název / Slug</th></tr></thead>
          <tbody>
            <tr><td>Stránka</td><td>Úvod (<code>uvod</code>) – nastavena jako úvodní stránka webu</td></tr>
            <tr><td>Stránka</td><td>Aktuality (<code>aktuality</code>)</td></tr>
            <tr><td>Stránka</td><td>Zápasy (<code>zapasy</code>)</td></tr>
            <tr><td>Stránka</td><td>Týmy (<code>tymy</code>)</td></tr>
            <tr><td>Stránka</td><td>Galerie (<code>galerie</code>)</td></tr>
            <tr><td>Stránka</td><td>Historie (<code>historie</code>)</td></tr>
            <tr><td>Stránka</td><td>Sponzoři (<code>sponzori</code>)</td></tr>
            <tr><td>Stránka</td><td>Kontakty (<code>kontakty</code>)</td></tr>
            <tr><td>Kategorie příspěvků</td><td>Aktuality (<code>aktuality</code>)</td></tr>
            <tr><td>Kategorie týmu</td><td>Muži A, Muži B, Dorost, Starší žáci, Mladší žáci, Přípravka</td></tr>
            <tr><td>Stav zápasu</td><td>Nadcházející, Odehraný, Zrušený</td></tr>
            <tr><td>Sezóna</td><td>2024/2025, 2025/2026</td></tr>
            <tr><td>Pozice hráče</td><td>Brankáři, Hráči v poli</td></tr>
          </tbody>
        </table>
      </details>

      <hr>

      <!-- SEKCE 2: Import ukázkových dat -->
      <h2>2. Import ukázkových dat</h2>
      <p>
        Importuje reálná data z veřejných zdrojů (fotbalunas.cz, Rokycanský deník).<br>
        Již existující záznamy jsou přeskočeny – akce lze bezpečně spustit opakovaně.
      </p>

      <table class="widefat striped" style="max-width:700px">
        <thead>
          <tr><th>Dataset</th><th>Obsah</th><th>Akce</th></tr>
        </thead>
        <tbody>

          <tr>
            <td><strong>Zápasy Muži A</strong></td>
            <td>16 odehraných + 6 nadcházejících<br><small>Plzeňský krajský přebor 2025/2026</small></td>
            <td>
              <form method="post" style="display:inline">
                <?php wp_nonce_field( 'tjsm_import_action', 'tjsm_import_nonce' ); ?>
                <input type="hidden" name="tjsm_import_action" value="zapasy_muzi_a">
                <?php submit_button( 'Importovat', 'primary', 'submit', false ); ?>
              </form>
            </td>
          </tr>

          <tr>
            <td><strong>Zápasy Muži B</strong></td>
            <td>13 odehraných + 3 nadcházející<br><small>1. B třída Plzeňský kraj, sk. C, 2025/2026</small></td>
            <td>
              <form method="post" style="display:inline">
                <?php wp_nonce_field( 'tjsm_import_action', 'tjsm_import_nonce' ); ?>
                <input type="hidden" name="tjsm_import_action" value="zapasy_muzi_b">
                <?php submit_button( 'Importovat', 'primary', 'submit', false ); ?>
              </form>
            </td>
          </tr>

          <tr>
            <td><strong>Hráči Muži A</strong></td>
            <td>19 hráčů (2 brankáři, 17 hráčů v poli)<br><small>Soupiska 2025/2026</small></td>
            <td>
              <form method="post" style="display:inline">
                <?php wp_nonce_field( 'tjsm_import_action', 'tjsm_import_nonce' ); ?>
                <input type="hidden" name="tjsm_import_action" value="hraci_muzi_a">
                <?php submit_button( 'Importovat', 'primary', 'submit', false ); ?>
              </form>
            </td>
          </tr>

        </tbody>
      </table>
      <p class="description" style="margin-top:.5em">
        <strong>Zdroje dat:</strong> fotbalunas.cz · fotbal.cz (FAČR) · sportmap.cz · Rokycanský deník
      </p>

    </div>
    <?php
}

// =====================================================================
// IMPORT UKÁZKOVÝCH DAT – POMOCNÉ FUNKCE
// =====================================================================

/**
 * Pomocná funkce – vloží jeden zápas jako CPT 'zapas'.
 * Přeskočí pokud již existuje zápas se stejným názvem a datem.
 */
function tjsm_insert_zapas( $data ) {
    $existing = get_posts( array(
        'post_type'   => 'zapas',
        'post_status' => 'publish',
        'title'       => $data['title'],
        'numberposts' => 1,
        'meta_query'  => array( array(
            'key'   => 'datum_zapasu',
            'value' => $data['datum'],
        ) ),
    ) );
    if ( $existing ) {
        return '✓ Zápas „' . $data['title'] . '" již existuje (přeskočen).';
    }

    $post_id = wp_insert_post( array(
        'post_title'  => $data['title'],
        'post_type'   => 'zapas',
        'post_status' => 'publish',
    ) );
    if ( is_wp_error( $post_id ) ) {
        return '✗ Chyba: ' . $post_id->get_error_message();
    }

    update_post_meta( $post_id, 'datum_zapasu', $data['datum'] );
    update_post_meta( $post_id, 'cas_zapasu',   $data['cas'] ?? '' );
    update_post_meta( $post_id, 'domaci',       $data['domaci'] );
    update_post_meta( $post_id, 'hoste',        $data['hoste'] );
    update_post_meta( $post_id, 'skore',        $data['skore'] ?? '' );
    update_post_meta( $post_id, 'strelci',      $data['strelci'] ?? '' );

    wp_set_object_terms( $post_id, $data['kategorie'], 'kategorie-tymu' );
    wp_set_object_terms( $post_id, '2025-2026',         'sezona' );
    wp_set_object_terms( $post_id, $data['stav'],       'stav-zapasu' );

    return '+ Zápas „' . $data['title'] . '" (' . $data['datum'] . ') vytvořen.';
}

/**
 * Pomocná funkce – vloží jednoho hráče jako CPT 'hrac'.
 */
function tjsm_insert_hrac( $data ) {
    $existing = get_posts( array(
        'post_type'   => 'hrac',
        'post_status' => 'publish',
        'title'       => $data['name'],
        'numberposts' => 1,
    ) );
    if ( $existing ) {
        return '✓ Hráč „' . $data['name'] . '" již existuje (přeskočen).';
    }

    $post_id = wp_insert_post( array(
        'post_title'  => $data['name'],
        'post_type'   => 'hrac',
        'post_status' => 'publish',
    ) );
    if ( is_wp_error( $post_id ) ) {
        return '✗ Chyba: ' . $post_id->get_error_message();
    }

    update_post_meta( $post_id, 'cislo',        $data['cislo'] ?? '' );
    update_post_meta( $post_id, 'rok_narozeni', $data['rok_narozeni'] ?? '' );
    update_post_meta( $post_id, 'tym_slug',     $data['tym_slug'] );

    wp_set_object_terms( $post_id, $data['kategorie'], 'kategorie-tymu' );
    wp_set_object_terms( $post_id, $data['pozice'],    'pozice-hrace' );

    return '+ Hráč „' . $data['name'] . '" vytvořen.';
}

/**
 * Import zápasů Muži A – sezóna 2025/2026.
 * Zdroj: fotbalunas.cz, Plzeňský krajský přebor.
 */
function tjsm_import_zapasy_muzi_a() {
    $log  = array();
    $myto = 'TJ Slavoj Mýto';

    $odehrane = array(
        array( 'datum' => '2025-08-08', 'domaci' => 'SK Rapid Plzeň',           'hoste' => $myto,                     'skore' => '4:2' ),
        array( 'datum' => '2025-08-17', 'domaci' => $myto,                      'hoste' => 'FK Nepomuk',              'skore' => '4:0' ),
        array( 'datum' => '2025-08-23', 'domaci' => 'SK Slavia Vejprnice',      'hoste' => $myto,                     'skore' => '2:3' ),
        array( 'datum' => '2025-08-30', 'domaci' => $myto,                      'hoste' => 'TJ Měcholupy',            'skore' => '5:2' ),
        array( 'datum' => '2025-09-06', 'domaci' => 'SK Horní Bříza',           'hoste' => $myto,                     'skore' => '3:0' ),
        array( 'datum' => '2025-09-13', 'domaci' => 'TJ Sokol Lhota',           'hoste' => $myto,                     'skore' => '4:0' ),
        array( 'datum' => '2025-09-21', 'domaci' => $myto,                      'hoste' => 'FC Dynamo Horšovský Týn', 'skore' => '3:2' ),
        array( 'datum' => '2025-09-27', 'domaci' => 'TJ Chotěšov',              'hoste' => $myto,                     'skore' => '2:2' ),
        array( 'datum' => '2025-10-04', 'domaci' => $myto,                      'hoste' => 'TJ Keramika Chlumčany',  'skore' => '3:2' ),
        array( 'datum' => '2025-10-12', 'domaci' => 'TJ Slavoj Koloveč',        'hoste' => $myto,                     'skore' => '0:1' ),
        array( 'datum' => '2025-10-18', 'domaci' => $myto,                      'hoste' => 'FK Bohemia Kaznějov',    'skore' => '2:1' ),
        array( 'datum' => '2025-10-26', 'domaci' => 'TJ Sokol Radnice',         'hoste' => $myto,                     'skore' => '0:6' ),
        array( 'datum' => '2025-11-01', 'domaci' => $myto,                      'hoste' => 'FK Okula Nýrsko',        'skore' => '0:1' ),
        array( 'datum' => '2025-11-09', 'domaci' => 'FK Holýšov',               'hoste' => $myto,                     'skore' => '0:2' ),
        array( 'datum' => '2025-11-15', 'domaci' => $myto,                      'hoste' => 'TJ Start Luby',          'skore' => '1:1' ),
        array( 'datum' => '2026-03-08', 'domaci' => $myto,                      'hoste' => 'SK Rapid Plzeň',         'skore' => '2:2' ),
    );

    foreach ( $odehrane as $z ) {
        $log[] = tjsm_insert_zapas( array(
            'title'     => $z['domaci'] . ' vs ' . $z['hoste'],
            'datum'     => $z['datum'],
            'cas'       => '10:30',
            'domaci'    => $z['domaci'],
            'hoste'     => $z['hoste'],
            'skore'     => $z['skore'],
            'kategorie' => 'muzi-a',
            'stav'      => 'odehrany',
        ) );
    }

    $nadchazejici = array(
        array( 'datum' => '2026-03-22', 'cas' => '15:00', 'domaci' => $myto, 'hoste' => 'SK Slavia Vejprnice' ),
        array( 'datum' => '2026-04-05', 'cas' => '16:30', 'domaci' => $myto, 'hoste' => 'SK Horní Bříza' ),
        array( 'datum' => '2026-04-11', 'cas' => '16:30', 'domaci' => $myto, 'hoste' => 'TJ Sokol Lhota' ),
        array( 'datum' => '2026-04-26', 'cas' => '17:00', 'domaci' => $myto, 'hoste' => 'TJ Chotěšov' ),
        array( 'datum' => '2026-05-09', 'cas' => '17:30', 'domaci' => $myto, 'hoste' => 'TJ Slavoj Koloveč' ),
        array( 'datum' => '2026-05-23', 'cas' => '18:00', 'domaci' => $myto, 'hoste' => 'TJ Sokol Radnice' ),
    );

    foreach ( $nadchazejici as $z ) {
        $log[] = tjsm_insert_zapas( array(
            'title'     => $z['domaci'] . ' vs ' . $z['hoste'],
            'datum'     => $z['datum'],
            'cas'       => $z['cas'],
            'domaci'    => $z['domaci'],
            'hoste'     => $z['hoste'],
            'skore'     => '',
            'kategorie' => 'muzi-a',
            'stav'      => 'nadchazejici',
        ) );
    }

    return $log;
}

/**
 * Import zápasů Muži B – sezóna 2025/2026.
 * Zdroj: fotbalunas.cz, 1. B třída Plzeňský kraj, sk. C.
 */
function tjsm_import_zapasy_muzi_b() {
    $log  = array();
    $myto = 'TJ Slavoj Mýto B';

    $odehrane = array(
        array( 'datum' => '2025-08-24', 'domaci' => $myto,                   'hoste' => 'SK Úněšov',          'skore' => '4:3' ),
        array( 'datum' => '2025-08-27', 'domaci' => 'TJ Všeruby',            'hoste' => $myto,                'skore' => '2:2' ),
        array( 'datum' => '2025-08-31', 'domaci' => 'FK Ledce',              'hoste' => $myto,                'skore' => '6:0' ),
        array( 'datum' => '2025-09-07', 'domaci' => $myto,                   'hoste' => 'TJ Touškov',         'skore' => '1:3' ),
        array( 'datum' => '2025-09-13', 'domaci' => 'TJ Město Zbiroh',       'hoste' => $myto,                'skore' => '7:2' ),
        array( 'datum' => '2025-09-21', 'domaci' => $myto,                   'hoste' => 'TJ Plasy',           'skore' => '1:2' ),
        array( 'datum' => '2025-09-27', 'domaci' => $myto,                   'hoste' => 'TJ Raková',          'skore' => '0:1' ),
        array( 'datum' => '2025-10-05', 'domaci' => 'SK SENCO Doubravka B',  'hoste' => $myto,                'skore' => '5:2' ),
        array( 'datum' => '2025-10-11', 'domaci' => $myto,                   'hoste' => 'TJ Volduchy',        'skore' => '5:2' ),
        array( 'datum' => '2025-10-19', 'domaci' => 'SK Rapid Plzeň B',      'hoste' => $myto,                'skore' => '7:2' ),
        array( 'datum' => '2025-10-25', 'domaci' => $myto,                   'hoste' => 'SK Horní Bříza B',   'skore' => '2:4' ),
        array( 'datum' => '2025-11-01', 'domaci' => 'TJ Bolevec',            'hoste' => $myto,                'skore' => '3:0' ),
        array( 'datum' => '2025-11-08', 'domaci' => $myto,                   'hoste' => 'TJ Příkosice',       'skore' => '3:2' ),
    );

    foreach ( $odehrane as $z ) {
        $log[] = tjsm_insert_zapas( array(
            'title'     => $z['domaci'] . ' vs ' . $z['hoste'],
            'datum'     => $z['datum'],
            'cas'       => '10:30',
            'domaci'    => $z['domaci'],
            'hoste'     => $z['hoste'],
            'skore'     => $z['skore'],
            'kategorie' => 'muzi-b',
            'stav'      => 'odehrany',
        ) );
    }

    $nadchazejici = array(
        array( 'datum' => '2026-03-28', 'cas' => '10:30', 'domaci' => $myto, 'hoste' => 'FK Ledce' ),
        array( 'datum' => '2026-04-12', 'cas' => '16:30', 'domaci' => $myto, 'hoste' => 'TJ Město Zbiroh' ),
        array( 'datum' => '2026-05-03', 'cas' => '17:30', 'domaci' => $myto, 'hoste' => 'SK SENCO Doubravka B' ),
    );

    foreach ( $nadchazejici as $z ) {
        $log[] = tjsm_insert_zapas( array(
            'title'     => $z['domaci'] . ' vs ' . $z['hoste'],
            'datum'     => $z['datum'],
            'cas'       => $z['cas'],
            'domaci'    => $z['domaci'],
            'hoste'     => $z['hoste'],
            'skore'     => '',
            'kategorie' => 'muzi-b',
            'stav'      => 'nadchazejici',
        ) );
    }

    return $log;
}

/**
 * Import hráčů Muži A – soupiska 2025/2026.
 * Zdroj: fotbalunas.cz, Transfermarkt, Rokycanský deník.
 */
function tjsm_import_hraci_muzi_a() {
    $log = array();

    $hraci = array(
        // Brankáři
        array( 'name' => 'Jan Vild',           'cislo' => 1,  'rok_narozeni' => 1987, 'pozice' => 'brankari' ),
        array( 'name' => 'Milan Navrátil',      'cislo' => 12, 'rok_narozeni' => 1990, 'pozice' => 'brankari' ),
        // Hráči v poli
        array( 'name' => 'Michal Ineman',       'cislo' => 2,  'rok_narozeni' => 1990, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Ondřej Lang',         'cislo' => 3,  'rok_narozeni' => 0,    'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Marek Šobáň',         'cislo' => 4,  'rok_narozeni' => 0,    'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Martin Drábek',       'cislo' => 5,  'rok_narozeni' => 0,    'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Jiří Drábek',         'cislo' => 6,  'rok_narozeni' => 2001, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Vojtech Krejca',      'cislo' => 7,  'rok_narozeni' => 2002, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Filip Stejskal',      'cislo' => 8,  'rok_narozeni' => 2000, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Daniel Skopový',      'cislo' => 9,  'rok_narozeni' => 1998, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Alexandr Čajkovskij', 'cislo' => 10, 'rok_narozeni' => 0,    'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Matěj Tůma',          'cislo' => 11, 'rok_narozeni' => 0,    'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Matyáš Mařík',        'cislo' => 13, 'rok_narozeni' => 2001, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Jan Mašek',           'cislo' => 14, 'rok_narozeni' => 2003, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Ondřej Mašek',        'cislo' => 15, 'rok_narozeni' => 2003, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Jakub Lenk',          'cislo' => 16, 'rok_narozeni' => 2003, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Marek Lupáč',         'cislo' => 17, 'rok_narozeni' => 1998, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Filip Renza',         'cislo' => 18, 'rok_narozeni' => 2002, 'pozice' => 'hraci-v-poli' ),
        array( 'name' => 'Martin Patrovský',    'cislo' => 19, 'rok_narozeni' => 2002, 'pozice' => 'hraci-v-poli' ),
    );

    foreach ( $hraci as $h ) {
        $log[] = tjsm_insert_hrac( array(
            'name'         => $h['name'],
            'cislo'        => $h['cislo'],
            'rok_narozeni' => $h['rok_narozeni'] > 0 ? $h['rok_narozeni'] : '',
            'tym_slug'     => 'muzi-a',
            'kategorie'    => 'muzi-a',
            'pozice'       => $h['pozice'],
        ) );
    }

    return $log;
}

/**
 * Zpracuje formulář importu ukázkových dat.
 */
function tjsm_import_handle_form() {
    if (
        ! isset( $_POST['tjsm_import_nonce'] ) ||
        ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tjsm_import_nonce'] ) ), 'tjsm_import_action' ) ||
        ! current_user_can( 'manage_options' )
    ) {
        return array();
    }

    $action = isset( $_POST['tjsm_import_action'] ) ? sanitize_key( $_POST['tjsm_import_action'] ) : '';

    switch ( $action ) {
        case 'zapasy_muzi_a': return tjsm_import_zapasy_muzi_a();
        case 'zapasy_muzi_b': return tjsm_import_zapasy_muzi_b();
        case 'hraci_muzi_a':  return tjsm_import_hraci_muzi_a();
        default:              return array( '✗ Neznámá akce.' );
    }
}

// =====================================================================
// UŽIVATELSKÉ ROLE
// =====================================================================

/**
 * Vlastní capability typy jsou nastavené na každém CPT (capability_type +
 * map_meta_cap). WordPress z nich generuje capabilities ve tvaru:
 *   edit_{singular}, edit_{plural}, edit_others_{plural}, publish_{plural},
 *   delete_{plural}, delete_others_{plural}, read_private_{plural} …
 *
 * Přehled rolí:
 *   1. Správce obsahu (slavoj_editor)   – vše kromě nastavení webu
 *   2. Zapisovatel    (slavoj_zapisovatel) – pouze editace zápasů
 *   3. Trenér         (slavoj_trener)      – hráči + týmy
 *   4. Fotograf       (slavoj_fotograf)    – galerie + nahrávání médií
 *   5. Redaktor       (slavoj_redaktor)    – příspěvky (aktuality)
 *
 * Role se vytvoří/aktualizuje jednou – uloží se do databáze (wp_options).
 * Verze (TJSM_ROLE_VERSION) zajistí přeregistraci při změně capabilities.
 */
define('TJSM_ROLE_VERSION', 2);

/**
 * Vrátí pole všech CPT capabilities pro daný capability_type.
 * Např. slavoj_cpt_caps('zapas', 'zapasy') vrátí edit_zapas, edit_zapasy, …
 */
function slavoj_cpt_caps($singular, $plural) {
    return array(
        "edit_{$singular}"           => true,
        "read_{$singular}"           => true,
        "delete_{$singular}"         => true,
        "edit_{$plural}"             => true,
        "edit_others_{$plural}"      => true,
        "edit_published_{$plural}"   => true,
        "publish_{$plural}"          => true,
        "read_private_{$plural}"     => true,
        "delete_{$plural}"           => true,
        "delete_others_{$plural}"    => true,
        "delete_published_{$plural}" => true,
        "delete_private_{$plural}"   => true,
        "edit_private_{$plural}"     => true,
    );
}

function slavoj_register_roles() {
    if ((int) get_option('tjsm_role_version') === TJSM_ROLE_VERSION) {
        return;
    }

    // Všechna CPT capabilities seskupená podle typu obsahu
    $caps_zapasy   = slavoj_cpt_caps('zapas',   'zapasy');
    $caps_tymy     = slavoj_cpt_caps('tym',     'tymy');
    $caps_hraci    = slavoj_cpt_caps('hrac',    'hraci');
    $caps_alba     = slavoj_cpt_caps('album',   'alba');
    $caps_sponzori = slavoj_cpt_caps('sponzor', 'sponzori');
    $caps_kontakty = slavoj_cpt_caps('kontakt', 'kontakty');

    $all_cpt_caps = array_merge(
        $caps_zapasy, $caps_tymy, $caps_hraci,
        $caps_alba, $caps_sponzori, $caps_kontakty
    );

    // ── 1. Administrátor – přidat vlastní CPT capabilities ──
    $admin = get_role('administrator');
    if ($admin) {
        foreach ($all_cpt_caps as $cap => $grant) {
            $admin->add_cap($cap);
        }
    }

    // ── 2. Správce obsahu – vše kromě nastavení webu ──
    remove_role('slavoj_editor');
    add_role('slavoj_editor', 'Správce obsahu', array_merge(
        array(
            'read'                   => true,
            'edit_posts'             => true,
            'edit_others_posts'      => true,
            'edit_published_posts'   => true,
            'publish_posts'          => true,
            'delete_posts'           => true,
            'delete_others_posts'    => true,
            'delete_published_posts' => true,
            'edit_pages'             => true,
            'edit_others_pages'      => true,
            'edit_published_pages'   => true,
            'publish_pages'          => true,
            'delete_pages'           => true,
            'delete_others_pages'    => true,
            'delete_published_pages' => true,
            'upload_files'           => true,
            'manage_categories'      => true,
        ),
        $all_cpt_caps
    ));

    // ── 3. Zapisovatel – pouze zápasy (editace skóre, střelců) ──
    remove_role('slavoj_zapisovatel');
    add_role('slavoj_zapisovatel', 'Zapisovatel', array_merge(
        array(
            'read'                   => true,
            'manage_categories'      => true,
        ),
        $caps_zapasy
    ));

    // ── 4. Trenér – hráči a týmy (správa soupisky) ──
    remove_role('slavoj_trener');
    add_role('slavoj_trener', 'Trenér', array_merge(
        array(
            'read'                   => true,
            'upload_files'           => true,
            'manage_categories'      => true,
        ),
        $caps_hraci,
        $caps_tymy
    ));

    // ── 5. Fotograf – galerie a nahrávání médií ──
    remove_role('slavoj_fotograf');
    add_role('slavoj_fotograf', 'Fotograf', array_merge(
        array(
            'read'                   => true,
            'upload_files'           => true,
            'manage_categories'      => true,
        ),
        $caps_alba
    ));

    // ── 6. Redaktor – příspěvky / aktuality ──
    remove_role('slavoj_redaktor');
    add_role('slavoj_redaktor', 'Redaktor', array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_published_posts'   => true,
        'publish_posts'          => true,
        'delete_posts'           => true,
        'upload_files'           => true,
    ));

    update_option('tjsm_role_version', TJSM_ROLE_VERSION);
}
add_action('init', 'slavoj_register_roles');

