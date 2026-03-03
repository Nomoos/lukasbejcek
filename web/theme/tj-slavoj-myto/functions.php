<?php
/**
 * TJ Slavoj Mýto - functions.php
 * Registrace menu, CPT, taxonomií, meta boxů a pomocných funkcí
 */

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

    // Hlavní šablona CSS (assets/css/main.css importuje všechny komponenty)
    wp_enqueue_style('slavoj-main', get_template_directory_uri() . '/assets/css/main.css', array('bootstrap'), $ver);
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
 * Vrátí zobrazovaný název kategorie týmu podle jeho slugu.
 *
 * @param string $slug  Slug taxonomie kategorie-tymu (např. 'muzi-a').
 * @return string  Zobrazovaný název (např. 'Muži A'), nebo samotný slug pokud není nalezen.
 */
function slavoj_get_team_display_name($slug) {
    $mapa = array(
        'muzi-a'      => 'Muži A',
        'muzi-b'      => 'Muži B',
        'dorost'      => 'Dorost',
        'starsi-zaci' => 'Starší žáci',
        'mladsi-zaci' => 'Mladší žáci',
        'pripravka'   => 'Přípravka',
    );
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
