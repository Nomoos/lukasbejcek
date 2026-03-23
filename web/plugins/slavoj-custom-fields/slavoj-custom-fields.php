<?php
/**
 * Plugin Name:       Slavoj Custom Fields
 * Plugin URI:        https://github.com/Nomoos/lukasbejcek
 * Description:       Vlastní meta pole (custom fields) pro CPT Zápasy, Týmy, Hráče, Galerie, Sponzory a Kontakty TJ Slavoj Mýto. Alternativa k pluginům Advanced Custom Fields a Custom Post Type UI.
 * Version:           1.0.0
 * Author:            Lukáš Bejček
 * License:           GPL-2.0-or-later
 * Text Domain:       slavoj-custom-fields
 *
 * Popis: Tento plugin registruje custom post types a meta boxy pro všechny typy obsahu
 * webu TJ Slavoj Mýto. Slouží jako interní náhrada za pluginy Custom Post Type UI
 * (registrace CPT a taxonomií) a Advanced Custom Fields (správa vlastních polí).
 * Vizuální rozhraní v administraci WordPress umožňuje správu obsahu bez nutnosti
 * editovat zdrojový kód.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Poznámka: CPT a taxonomie jsou registrovány v functions.php tématu.
// Tento plugin poskytuje rozšiřující administrační funkce a nástrojové stránky.

// =====================================================================
// ADMINISTRAČNÍ NÁSTROJOVÁ STRÁNKA
// =====================================================================

function slavoj_cf_admin_menu() {
    add_submenu_page(
        'tools.php',
        'Slavoj – Průvodce nastavením',
        'Slavoj nastavení',
        'manage_options',
        'slavoj-setup',
        'slavoj_cf_setup_page'
    );
}
add_action('admin_menu', 'slavoj_cf_admin_menu');

function slavoj_cf_setup_page() {
    ?>
    <div class="wrap">
      <h1>TJ Slavoj Mýto – Průvodce nastavením</h1>
      <p>Tento plugin poskytuje vlastní meta pole pro téma TJ Slavoj Mýto. Níže je přehled registrovaných typů obsahu a jejich polí.</p>

      <h2>Registrované typy obsahu (CPT)</h2>
      <table class="widefat striped">
        <thead>
          <tr>
            <th>Typ obsahu</th>
            <th>Slug</th>
            <th>Popis</th>
            <th>Vlastní pole</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Zápasy</strong></td>
            <td><code>zapas</code></td>
            <td>Fotbalové zápasy s výsledky a detaily</td>
            <td>datum_zapasu, cas_zapasu, domaci, hoste, skore, strelci</td>
          </tr>
          <tr>
            <td><strong>Týmy</strong></td>
            <td><code>tym</code></td>
            <td>Přehled týmů a jejich soupiska</td>
            <td>tym_slug, pocet_hracu, hlavni_trener, asistent_trenera, zdravotnik</td>
          </tr>
          <tr>
            <td><strong>Hráči</strong></td>
            <td><code>hrac</code></td>
            <td>Hráčské profily se číslem dresu a rokem narození</td>
            <td>cislo, rok_narozeni, tym_slug</td>
          </tr>
          <tr>
            <td><strong>Galerie</strong></td>
            <td><code>galerie</code></td>
            <td>Fotogalerie a fotoalba z akcí</td>
            <td>tym, sezona</td>
          </tr>
          <tr>
            <td><strong>Sponzoři</strong></td>
            <td><code>sponzor</code></td>
            <td>Partneři a sponzoři klubu</td>
            <td>web_sponzora</td>
          </tr>
          <tr>
            <td><strong>Kontakty</strong></td>
            <td><code>kontakt</code></td>
            <td>Výbor klubu a kontaktní osoby</td>
            <td>pozice, telefon, email, poradi</td>
          </tr>
        </tbody>
      </table>

      <h2>Registrované taxonomie</h2>
      <table class="widefat striped">
        <thead>
          <tr>
            <th>Taxonomie</th>
            <th>Slug</th>
            <th>Používá se v</th>
            <th>Příklady hodnot</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Sezóna</strong></td>
            <td><code>sezona</code></td>
            <td>zapas, tym, hrac, galerie</td>
            <td>2024/25, 2025/26</td>
          </tr>
          <tr>
            <td><strong>Kategorie týmu</strong></td>
            <td><code>kategorie-tymu</code></td>
            <td>zapas, tym, hrac, galerie</td>
            <td>muzi-a, muzi-b, dorost, starsi-zaci</td>
          </tr>
          <tr>
            <td><strong>Stav zápasu</strong></td>
            <td><code>stav-zapasu</code></td>
            <td>zapas</td>
            <td>nadchazejici, odehrany, zruseny</td>
          </tr>
          <tr>
            <td><strong>Pozice hráče</strong></td>
            <td><code>pozice-hrace</code></td>
            <td>hrac</td>
            <td>brankari, hraci-v-poli</td>
          </tr>
        </tbody>
      </table>

      <h2>Rychlé nastavení – přidat výchozí taxonomie</h2>
      <p>Kliknutím na tlačítko níže automaticky vytvoříte výchozí hodnoty taxonomií (sezóny, kategorie týmů, stavy zápasů a pozice hráčů).</p>
      <form method="post">
        <?php wp_nonce_field('slavoj_seed_taxonomies', 'slavoj_seed_nonce'); ?>
        <input type="hidden" name="slavoj_action" value="seed_taxonomies">
        <?php submit_button('Vytvořit výchozí hodnoty taxonomií', 'primary', 'slavoj_seed_submit'); ?>
      </form>

      <?php
      if (isset($_POST['slavoj_action']) && $_POST['slavoj_action'] === 'seed_taxonomies') {
          if (check_admin_referer('slavoj_seed_taxonomies', 'slavoj_seed_nonce')) {
              slavoj_cf_seed_taxonomies();
              echo '<div class="notice notice-success"><p>✅ Výchozí taxonomie byly úspěšně vytvořeny!</p></div>';
          }
      }
      ?>

      <h2>Ukázková data – testovací obsah</h2>
      <p>Kliknutím níže naplníte web ukázkovými záznamy zápasů, týmu, hráčů, kontaktů a galerie extrahovanými z původního kódu (<code>original/</code>). Duplicity jsou automaticky přeskočeny.</p>
      <form method="post">
        <?php wp_nonce_field('slavoj_seed_demo', 'slavoj_demo_nonce'); ?>
        <input type="hidden" name="slavoj_action" value="seed_demo">
        <?php submit_button('Vytvořit ukázková data', 'secondary', 'slavoj_demo_submit'); ?>
      </form>

      <?php
      if (isset($_POST['slavoj_action']) && $_POST['slavoj_action'] === 'seed_demo') {
          if (check_admin_referer('slavoj_seed_demo', 'slavoj_demo_nonce')) {
              $result = slavoj_cf_seed_demo_data();
              echo '<div class="notice notice-success"><p>✅ Ukázková data byla úspěšně vytvořena! Přidáno: ' . intval($result) . ' nových záznamů.</p></div>';
          }
      }
      ?>

      <h2>WordPress stránky – vytvoření navigačních stránek</h2>
      <p>Kliknutím níže automaticky vytvoříte WordPress stránky (<em>Stránky → Všechny stránky</em>) pro každou sekci webu (Domů, Zápasy, Týmy, Galerie, Sponzoři, Kontakty, Historie). Stránkám jsou přiřazeny správné šablony tématu. Duplicity jsou přeskočeny.</p>
      <form method="post">
        <?php wp_nonce_field('slavoj_seed_pages', 'slavoj_pages_nonce'); ?>
        <input type="hidden" name="slavoj_action" value="seed_pages">
        <?php submit_button('Vytvořit stránky webu', 'primary', 'slavoj_pages_submit'); ?>
      </form>

      <?php
      if (isset($_POST['slavoj_action']) && $_POST['slavoj_action'] === 'seed_pages') {
          if (check_admin_referer('slavoj_seed_pages', 'slavoj_pages_nonce')) {
              $result = slavoj_cf_seed_pages();
              echo '<div class="notice notice-success"><p>✅ Stránky webu byly úspěšně vytvořeny! Přidáno: ' . intval($result) . ' nových stránek.</p></div>';
          }
      }
      ?>

      <h2>Nápověda</h2>
      <ol>
        <li>Přejděte do menu <strong>Zápasy → Přidat zápas</strong> a vyplňte detaily zápasu.</li>
        <li>V postranním panelu přiřaďte <strong>Kategorii týmu</strong> (Muži A, Muži B…) a <strong>Sezónu</strong>.</li>
        <li>Pro zobrazení hráčů v soupisce přidejte hráče přes <strong>Hráči → Přidat hráče</strong> a v poli „Slug týmu" zadejte slug odpovídající kategorie (např. <code>muzi-a</code>).</li>
        <li>Galerie fotek – přidejte fotoalbum přes <strong>Galerie → Přidat album</strong> a vložte fotografie do obsahu příspěvku nebo jako obrázek příspěvku.</li>
      </ol>
    </div>
    <?php
}

// =====================================================================
// SEED – vytvoření výchozích hodnot taxonomií
// =====================================================================

function slavoj_cf_seed_taxonomies() {
    // Sezóny
    $sezony = array('2025/26', '2024/25', '2023/24');
    foreach ($sezony as $s) {
        if (!term_exists($s, 'sezona')) {
            wp_insert_term($s, 'sezona');
        }
    }

    // Kategorie týmů
    $kategorie = array(
        'Muži A'            => 'muzi-a',
        'Muži B'            => 'muzi-b',
        'Stará garda'       => 'stara-garda',
        'Dorost'            => 'dorost',
        'Starší žáci'       => 'starsi-zaci',
        'Mladší žáci'       => 'mladsi-zaci',
        'Starší přípravka'  => 'starsi-pripravka',
        'Mladší přípravka'  => 'mladsi-pripravka',
        'Mini přípravka'    => 'mini-pripravka',
    );
    foreach ($kategorie as $nazev => $slug) {
        if (!term_exists($slug, 'kategorie-tymu')) {
            wp_insert_term($nazev, 'kategorie-tymu', array('slug' => $slug));
        }
    }

    // Stavy zápasů
    $stavy = array(
        'Nadcházející' => 'nadchazejici',
        'Odehraný'     => 'odehrany',
        'Zrušený'      => 'zruseny',
    );
    foreach ($stavy as $nazev => $slug) {
        if (!term_exists($slug, 'stav-zapasu')) {
            wp_insert_term($nazev, 'stav-zapasu', array('slug' => $slug));
        }
    }

    // Pozice hráčů
    $pozice = array(
        'Brankáři'       => 'brankari',
        'Hráči v poli'   => 'hraci-v-poli',
    );
    foreach ($pozice as $nazev => $slug) {
        if (!term_exists($slug, 'pozice-hrace')) {
            wp_insert_term($nazev, 'pozice-hrace', array('slug' => $slug));
        }
    }
}

// Spustit seed při aktivaci pluginu
register_activation_hook(__FILE__, 'slavoj_cf_seed_taxonomies');

// =====================================================================
// ROZŠÍŘENÍ SLOUPCŮ V ADMIN PŘEHLEDU – ZÁPASY
// =====================================================================

function slavoj_zapas_admin_columns($columns) {
    $new = array();
    foreach ($columns as $key => $title) {
        $new[$key] = $title;
        if ($key === 'title') {
            $new['datum_zapasu'] = 'Datum zápasu';
            $new['tym']          = 'Tým';
            $new['skore']        = 'Skóre';
        }
    }
    return $new;
}
add_filter('manage_zapas_posts_columns', 'slavoj_zapas_admin_columns');

function slavoj_zapas_admin_column_data($column, $post_id) {
    if ($column === 'datum_zapasu') {
        $datum = get_post_meta($post_id, 'datum_zapasu', true);
        if ($datum) {
            $dt = DateTime::createFromFormat('Y-m-d', $datum);
            echo $dt ? esc_html($dt->format('j. n. Y')) : esc_html($datum);
        }
    }
    if ($column === 'tym') {
        $terms = get_the_terms($post_id, 'kategorie-tymu');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html($terms[0]->name);
        }
    }
    if ($column === 'skore') {
        $skore = get_post_meta($post_id, 'skore', true);
        echo $skore ? esc_html($skore) : '—';
    }
}
add_action('manage_zapas_posts_custom_column', 'slavoj_zapas_admin_column_data', 10, 2);

// =====================================================================
// ROZŠÍŘENÍ SLOUPCŮ V ADMIN PŘEHLEDU – HRÁČI
// =====================================================================

function slavoj_hrac_admin_columns($columns) {
    $new = array();
    foreach ($columns as $key => $title) {
        $new[$key] = $title;
        if ($key === 'title') {
            $new['cislo']    = 'Číslo dresu';
            $new['pozice']   = 'Pozice';
            $new['tym_hrac'] = 'Tým';
        }
    }
    return $new;
}
add_filter('manage_hrac_posts_columns', 'slavoj_hrac_admin_columns');

function slavoj_hrac_admin_column_data($column, $post_id) {
    if ($column === 'cislo') {
        echo esc_html(get_post_meta($post_id, 'cislo', true));
    }
    if ($column === 'pozice') {
        $terms = get_the_terms($post_id, 'pozice-hrace');
        if ($terms && !is_wp_error($terms)) {
            echo esc_html($terms[0]->name);
        }
    }
    if ($column === 'tym_hrac') {
        $slug = get_post_meta($post_id, 'tym_slug', true);
        echo esc_html( function_exists( 'slavoj_get_team_display_name' ) ? slavoj_get_team_display_name( $slug ) : $slug );
    }
}
add_action('manage_hrac_posts_custom_column', 'slavoj_hrac_admin_column_data', 10, 2);

// =====================================================================
// ŘAZENÍ SLOUPCŮ V ADMIN PŘEHLEDU – ZÁPASY PODLE DATA
// =====================================================================

function slavoj_zapas_sortable_columns($columns) {
    $columns['datum_zapasu'] = 'datum_zapasu';
    return $columns;
}
add_filter('manage_edit-zapas_sortable_columns', 'slavoj_zapas_sortable_columns');

function slavoj_zapas_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->get('orderby') === 'datum_zapasu') {
        $query->set('meta_key', 'datum_zapasu');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'slavoj_zapas_orderby');

// =====================================================================
// DROPDOWN FILTRY V ADMIN PŘEHLEDU
// =====================================================================

/**
 * Přidá dropdown filtry nad seznamem příspěvků v admin přehledu.
 *
 * - Zápasy:  sezóna + kategorie týmu
 * - Hráči:   sezóna + tým (podle meta pole tym_slug)
 * - Týmy:    sezóna
 * - Galerie: kategorie týmu
 */
function slavoj_admin_filters( $post_type ) {

    // Mapa: post_type => pole taxonomií, které se mají zobrazit jako dropdown
    $tax_filters = array(
        'zapas'   => array( 'sezona', 'kategorie-tymu' ),
        'hrac'    => array( 'sezona' ),
        'tym'     => array( 'sezona' ),
        'galerie' => array( 'sezona', 'kategorie-tymu' ),
    );

    if ( isset( $tax_filters[ $post_type ] ) ) {
        foreach ( $tax_filters[ $post_type ] as $taxonomy ) {
            $tax_obj = get_taxonomy( $taxonomy );
            if ( ! $tax_obj ) {
                continue;
            }
            $terms = get_terms( array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ) );
            if ( is_wp_error( $terms ) || empty( $terms ) ) {
                continue;
            }
            // Řazení kategorie-tymu je zajištěno globálním filtrem
            // slavoj_global_sort_kategorie_tymu() v functions.php.
            // Stará garda se zobrazuje jen v galerii.
            if ( 'kategorie-tymu' === $taxonomy && 'galerie' !== $post_type ) {
                $terms = array_filter( $terms, function( $term ) {
                    return 'stara-garda' !== $term->slug;
                } );
            }
            $selected = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) : '';
            echo '<select name="' . esc_attr( $taxonomy ) . '">';
            echo '<option value="">' . esc_html( $tax_obj->labels->all_items ) . '</option>';
            foreach ( $terms as $term ) {
                printf(
                    '<option value="%s"%s>%s (%d)</option>',
                    esc_attr( $term->slug ),
                    selected( $selected, $term->slug, false ),
                    esc_html( $term->name ),
                    (int) $term->count
                );
            }
            echo '</select>';
        }
    }

    // Hráči: speciální dropdown pro tým (meta pole tym_slug → název týmu)
    if ( 'hrac' === $post_type ) {
        slavoj_admin_filter_tym_slug();
    }
}
add_action( 'restrict_manage_posts', 'slavoj_admin_filters' );

/**
 * Vykreslí dropdown pro filtrování hráčů podle tym_slug.
 * Načte všechny publikované týmy a zobrazí je jako volby.
 */
function slavoj_admin_filter_tym_slug() {
    $tymy = get_posts( array(
        'post_type'      => 'tym',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    if ( empty( $tymy ) ) {
        return;
    }

    // Kanonické řazení podle slavoj_kategorie_poradi() (Muži A → Muži B → Dorost → …)
    if ( function_exists( 'slavoj_kategorie_poradi' ) ) {
        $poradi = array_keys( slavoj_kategorie_poradi() );
        usort( $tymy, function ( $a, $b ) use ( $poradi ) {
            $sa = get_post_meta( $a->ID, 'tym_slug', true );
            $sb = get_post_meta( $b->ID, 'tym_slug', true );
            $ia = array_search( $sa, $poradi );
            $ib = array_search( $sb, $poradi );
            $ia = ( $ia === false ) ? 999 : $ia;
            $ib = ( $ib === false ) ? 999 : $ib;
            return $ia - $ib;
        } );
    }

    $selected = isset( $_GET['filter_tym_slug'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_tym_slug'] ) ) : '';

    echo '<select name="filter_tym_slug">';
    echo '<option value="">Všechny týmy</option>';
    foreach ( $tymy as $tym ) {
        $slug = get_post_meta( $tym->ID, 'tym_slug', true );
        if ( ! $slug ) {
            continue;
        }
        printf(
            '<option value="%s"%s>%s</option>',
            esc_attr( $slug ),
            selected( $selected, $slug, false ),
            esc_html( $tym->post_title )
        );
    }
    echo '</select>';
}

/**
 * Zpracuje taxonomy dropdown filtry a meta filtr tym_slug v admin WP_Query.
 */
function slavoj_admin_filter_query( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $pt = $query->get( 'post_type' );

    // Taxonomy filtry – WordPress je zpracuje sám pouze pokud je taxonomy
    // registrovaná na daném post type A je přítomen query var.
    // Protože naše taxonomie mají show_admin_column => true a jsou
    // registrovány na příslušných CPT, WP je zpracovává nativně.
    // Stačí zpracovat meta filtr pro hráče.

    if ( 'hrac' === $pt && ! empty( $_GET['filter_tym_slug'] ) ) {
        $slug = sanitize_text_field( wp_unslash( $_GET['filter_tym_slug'] ) );
        $meta = $query->get( 'meta_query' );
        if ( ! is_array( $meta ) ) {
            $meta = array();
        }
        $meta[] = array(
            'key'     => 'tym_slug',
            'value'   => $slug,
            'compare' => '=',
        );
        $query->set( 'meta_query', $meta );
    }
}
add_action( 'pre_get_posts', 'slavoj_admin_filter_query' );

// =====================================================================
// ŘAZENÍ SLOUPCŮ – HRÁČI PODLE ČÍSLA DRESU
// =====================================================================

function slavoj_hrac_sortable_columns( $columns ) {
    $columns['cislo']    = 'cislo';
    $columns['tym_hrac'] = 'tym_hrac';
    return $columns;
}
add_filter( 'manage_edit-hrac_sortable_columns', 'slavoj_hrac_sortable_columns' );

function slavoj_hrac_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( 'hrac' !== $query->get( 'post_type' ) ) {
        return;
    }
    $orderby = $query->get( 'orderby' );
    if ( 'cislo' === $orderby ) {
        $query->set( 'meta_key', 'cislo' );
        $query->set( 'orderby', 'meta_value_num' );
    }
    if ( 'tym_hrac' === $orderby ) {
        $query->set( 'meta_key', 'tym_slug' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'slavoj_hrac_orderby' );

// =====================================================================
// SEED – ukázková data pro testování (extrahována z original/)
// =====================================================================

/**
 * Vytvoří ukázková data (zápasy, tým, hráče, kontakty, galerie) pro testování.
 * Data jsou přebírána z původního kódu v original/ HTML souborech.
 * Duplicity jsou přeskočeny na základě shody post_title a post_type.
 *
 * @return int Počet nově vytvořených záznamů.
 */
function slavoj_cf_seed_demo_data() {
    // Nejprve zajistíme existenci taxonomií
    slavoj_cf_seed_taxonomies();

    $created = 0;

    // ------------------------------------------------------------------
    // ZÁPASY (zapas) – reálné výsledky + nadcházející, sezóna 2025/26
    // Zdroj: fotbalunas.cz, sportmap.cz (březen 2026)
    // ------------------------------------------------------------------
    $zapasy = array(
        // ---- Odehrané zápasy Muži A ----
        array(
            'title'        => 'TJ Slavoj Mýto vs SK Nepomuk',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'SK Nepomuk',
            'datum_zapasu' => '2025-09-06',
            'cas_zapasu'   => '17:00',
            'skore'        => '4:0',
            'strelci'      => '2× Křivánek, Vaněček, Drábek M.',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Měcholupy',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Měcholupy',
            'datum_zapasu' => '2025-09-20',
            'cas_zapasu'   => '17:00',
            'skore'        => '5:2',
            'strelci'      => '3× Křivánek, Vaněček, Čajkovskij',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'SK Radnice vs TJ Slavoj Mýto',
            'domaci'       => 'SK Radnice',
            'hoste'        => 'TJ Slavoj Mýto',
            'datum_zapasu' => '2025-10-04',
            'cas_zapasu'   => '16:30',
            'skore'        => '0:6',
            'strelci'      => '3× Vaněček, 2× Křivánek, Tůma',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Holýšov',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Holýšov',
            'datum_zapasu' => '2025-10-18',
            'cas_zapasu'   => '16:00',
            'skore'        => '2:0',
            'strelci'      => 'Křivánek, Lang',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'Rapid Plzeň vs TJ Slavoj Mýto',
            'domaci'       => 'Rapid Plzeň',
            'hoste'        => 'TJ Slavoj Mýto',
            'datum_zapasu' => '2025-11-01',
            'cas_zapasu'   => '16:00',
            'skore'        => '4:2',
            'strelci'      => 'Vaněček, Drábek M.',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Chotěšov',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Chotěšov',
            'datum_zapasu' => '2025-11-15',
            'cas_zapasu'   => '15:00',
            'skore'        => '2:2',
            'strelci'      => 'Křivánek, Čajkovskij',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        array(
            'title'        => 'SK Horní Bříza vs TJ Slavoj Mýto',
            'domaci'       => 'SK Horní Bříza',
            'hoste'        => 'TJ Slavoj Mýto',
            'datum_zapasu' => '2026-03-07',
            'cas_zapasu'   => '15:00',
            'skore'        => '3:0',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'odehrany',
        ),
        // ---- Nadcházející zápasy Muži A ----
        array(
            'title'        => 'TJ Slavoj Mýto vs SK Slavia Vejprnice',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'SK Slavia Vejprnice',
            'datum_zapasu' => '2026-03-22',
            'cas_zapasu'   => '15:00',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'nadchazejici',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs SK Horní Bříza',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'SK Horní Bříza',
            'datum_zapasu' => '2026-04-05',
            'cas_zapasu'   => '16:30',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'nadchazejici',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Sokol Lhota',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Sokol Lhota',
            'datum_zapasu' => '2026-04-11',
            'cas_zapasu'   => '16:30',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'nadchazejici',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Chotěšov (jaro)',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Chotěšov',
            'datum_zapasu' => '2026-04-26',
            'cas_zapasu'   => '17:00',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'nadchazejici',
        ),
        array(
            'title'        => 'TJ Slavoj Mýto vs TJ Slavoj Koloveč',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'TJ Slavoj Koloveč',
            'datum_zapasu' => '2026-05-09',
            'cas_zapasu'   => '17:30',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-a',
            'stav'         => 'nadchazejici',
        ),
        // ---- Muži B ----
        array(
            'title'        => 'TJ Slavoj Mýto B vs TJ Sokol Zbiroh',
            'domaci'       => 'TJ Slavoj Mýto B',
            'hoste'        => 'TJ Sokol Zbiroh',
            'datum_zapasu' => '2026-04-05',
            'cas_zapasu'   => '14:00',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'muzi-b',
            'stav'         => 'nadchazejici',
        ),
        // ---- Dorost ----
        array(
            'title'        => 'TJ Slavoj Mýto vs FK Rokycany (Dorost)',
            'domaci'       => 'TJ Slavoj Mýto',
            'hoste'        => 'FK Rokycany',
            'datum_zapasu' => '2026-04-12',
            'cas_zapasu'   => '10:00',
            'skore'        => '',
            'strelci'      => '',
            'sezona'       => '2025/26',
            'kategorie'    => 'dorost',
            'stav'         => 'nadchazejici',
        ),
    );

    foreach ($zapasy as $z) {
        if (slavoj_post_exists($z['title'], 'zapas')) {
            continue;
        }
        $post_id = wp_insert_post(array(
            'post_title'  => $z['title'],
            'post_type'   => 'zapas',
            'post_status' => 'publish',
        ));
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'domaci',       $z['domaci']);
            update_post_meta($post_id, 'hoste',        $z['hoste']);
            update_post_meta($post_id, 'datum_zapasu', $z['datum_zapasu']);
            update_post_meta($post_id, 'cas_zapasu',   $z['cas_zapasu']);
            update_post_meta($post_id, 'skore',        $z['skore']);
            update_post_meta($post_id, 'strelci',      $z['strelci']);
            wp_set_object_terms($post_id, $z['sezona'],    'sezona');
            wp_set_object_terms($post_id, $z['kategorie'], 'kategorie-tymu');
            wp_set_object_terms($post_id, $z['stav'],      'stav-zapasu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // TÝMY (tym)
    // Zdroj: slavojmyto.cz, fotbalunas.cz
    // ------------------------------------------------------------------
    $tymy = array(
        array(
            'title'     => 'Muži A',
            'slug'      => 'muzi-a',
            'trener'    => 'Petr Nykles',
            'asistent'  => 'Ivan Honzík',
            'zdravotnik'=> 'Jan Basl',
            'pocet'     => 20,
            'sezona'    => '2025/26',
        ),
        array(
            'title'     => 'Muži B',
            'slug'      => 'muzi-b',
            'trener'    => 'Martin Hoffmann',
            'asistent'  => '',
            'zdravotnik'=> '',
            'pocet'     => 16,
            'sezona'    => '2025/26',
        ),
        array(
            'title'     => 'Dorost',
            'slug'      => 'dorost',
            'trener'    => 'Petr Bejček',
            'asistent'  => '',
            'zdravotnik'=> '',
            'pocet'     => 18,
            'sezona'    => '2025/26',
        ),
    );

    foreach ($tymy as $t) {
        if (slavoj_post_exists($t['title'], 'tym')) {
            continue;
        }
        $tym_id = wp_insert_post(array(
            'post_title'  => $t['title'],
            'post_type'   => 'tym',
            'post_status' => 'publish',
        ));
        if ($tym_id && !is_wp_error($tym_id)) {
            update_post_meta($tym_id, 'tym_slug',          $t['slug']);
            update_post_meta($tym_id, 'hlavni_trener',     $t['trener']);
            update_post_meta($tym_id, 'asistent_trenera',  $t['asistent']);
            update_post_meta($tym_id, 'zdravotnik',        $t['zdravotnik']);
            update_post_meta($tym_id, 'pocet_hracu',       $t['pocet']);
            wp_set_object_terms($tym_id, $t['sezona'], 'sezona');
            wp_set_object_terms($tym_id, $t['slug'],   'kategorie-tymu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // HRÁČI (hrac) – Muži A, reálná soupiska
    // Zdroj: slavojmyto.cz/cs/tymy/muzi-a, fotbalunas.cz
    // ------------------------------------------------------------------
    $hraci = array(
        // Brankáři
        array('title' => 'Milan Navrátil',      'cislo' => 1,  'rok' => 1993, 'pozice' => 'brankari',    'tym' => 'muzi-a'),
        // Hráči v poli
        array('title' => 'Ondřej Lang',         'cislo' => 4,  'rok' => 1999, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Marek Šobáň',         'cislo' => 6,  'rok' => 1997, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Martin Drábek',       'cislo' => 8,  'rok' => 1998, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Alexandr Čajkovskij', 'cislo' => 9,  'rok' => 2001, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Matěj Tůma',          'cislo' => 10, 'rok' => 2003, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Lukáš Křivánek',      'cislo' => 11, 'rok' => 1998, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jiří Drábek',         'cislo' => 13, 'rok' => 2000, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'David Vaněček',       'cislo' => 17, 'rok' => 1995, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Marek Lupáč',         'cislo' => 19, 'rok' => 1998, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jakub Lenk',          'cislo' => 20, 'rok' => 2003, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jan Vild',            'cislo' => 21, 'rok' => 1987, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Matyáš Mařík',        'cislo' => 22, 'rok' => 2001, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jan Mašek',           'cislo' => 23, 'rok' => 2003, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Ondřej Mašek',        'cislo' => 24, 'rok' => 2003, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        // Muži B
        array('title' => 'Filip Stejskal',      'cislo' => 1,  'rok' => 2000, 'pozice' => 'brankari',    'tym' => 'muzi-b'),
        array('title' => 'Dominik Wood',        'cislo' => 5,  'rok' => 2002, 'pozice' => 'hraci-v-poli','tym' => 'muzi-b'),
        array('title' => 'Martin Hoffmann',     'cislo' => 7,  'rok' => 1990, 'pozice' => 'hraci-v-poli','tym' => 'muzi-b'),
    );

    foreach ($hraci as $h) {
        if (slavoj_post_exists($h['title'], 'hrac')) {
            continue;
        }
        $hrac_id = wp_insert_post(array(
            'post_title'  => $h['title'],
            'post_type'   => 'hrac',
            'post_status' => 'publish',
        ));
        if ($hrac_id && !is_wp_error($hrac_id)) {
            update_post_meta($hrac_id, 'cislo',        $h['cislo']);
            update_post_meta($hrac_id, 'rok_narozeni', $h['rok']);
            update_post_meta($hrac_id, 'tym_slug',     $h['tym']);
            wp_set_object_terms($hrac_id, '2025/26',   'sezona');
            wp_set_object_terms($hrac_id, $h['tym'],   'kategorie-tymu');
            wp_set_object_terms($hrac_id, $h['pozice'],'pozice-hrace');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // KONTAKTY (kontakt) – Výbor klubu
    // Zdroj: slavojmyto.cz
    // ------------------------------------------------------------------
    $kontakty = array(
        array('title' => 'Radek Koula',      'pozice' => 'Předseda klubu',                   'telefon' => '+420 602 224 684', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 1),
        array('title' => 'Milan Huml',       'pozice' => 'Sekretář klubu a administrativa',  'telefon' => '+420 737 259 684', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 2),
        array('title' => 'František Končel', 'pozice' => 'Pokladník',                        'telefon' => '+420 737 151 288', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 3),
        array('title' => 'Petr Bejček',      'pozice' => 'Člen výboru, vedoucí mládeže',     'telefon' => '+420 776 137 057', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 4),
        array('title' => 'Petr Nykles',      'pozice' => 'Hlavní trenér Mužů A',             'telefon' => '',                 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 5),
        array('title' => 'Ivan Honzík',      'pozice' => 'Asistent trenéra Mužů A',          'telefon' => '',                 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 6),
    );

    foreach ($kontakty as $k) {
        if (slavoj_post_exists($k['title'], 'kontakt')) {
            continue;
        }
        $k_id = wp_insert_post(array(
            'post_title'  => $k['title'],
            'post_type'   => 'kontakt',
            'post_status' => 'publish',
        ));
        if ($k_id && !is_wp_error($k_id)) {
            update_post_meta($k_id, 'pozice',  $k['pozice']);
            update_post_meta($k_id, 'telefon', $k['telefon']);
            update_post_meta($k_id, 'email',   $k['email']);
            update_post_meta($k_id, 'poradi',  $k['poradi']);
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // SPONZOŘI (sponzor)
    // ------------------------------------------------------------------
    $sponzori = array(
        array('title' => 'Město Mýto',          'web' => 'https://www.myto.cz'),
        array('title' => 'Rokycany – kraj',     'web' => 'https://www.plzensky-kraj.cz'),
        array('title' => 'Místní sponzor',      'web' => ''),
    );

    foreach ($sponzori as $s) {
        if (slavoj_post_exists($s['title'], 'sponzor')) {
            continue;
        }
        $s_id = wp_insert_post(array(
            'post_title'  => $s['title'],
            'post_type'   => 'sponzor',
            'post_status' => 'publish',
        ));
        if ($s_id && !is_wp_error($s_id)) {
            update_post_meta($s_id, 'web_sponzora', $s['web']);
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // GALERIE (galerie)
    // ------------------------------------------------------------------
    $galerie = array(
        array('title' => 'TJ Slavoj Mýto vs SK Nepomuk 4:0',   'kategorie' => 'muzi-a',    'sezona' => '2025/26'),
        array('title' => 'Předzápasový trénink – podzim 2025', 'kategorie' => 'muzi-a',    'sezona' => '2025/26'),
        array('title' => 'Horní Bříza vs Mýto',                'kategorie' => 'muzi-a',    'sezona' => '2025/26'),
        array('title' => 'Mýto vs Lhota – 4. kolo',            'kategorie' => 'muzi-a',    'sezona' => '2024/25'),
        array('title' => 'Turnaj mladší žáci – Rokycany',      'kategorie' => 'mladsi-zaci','sezona' => '2024/25'),
        array('title' => 'Letní příprava dorostu 2025',        'kategorie' => 'dorost',    'sezona' => '2025/26'),
    );

    foreach ($galerie as $g) {
        if (slavoj_post_exists($g['title'], 'galerie')) {
            continue;
        }
        $g_id = wp_insert_post(array(
            'post_title'  => $g['title'],
            'post_type'   => 'galerie',
            'post_status' => 'publish',
        ));
        if ($g_id && !is_wp_error($g_id)) {
            wp_set_object_terms($g_id, $g['sezona'],    'sezona');
            wp_set_object_terms($g_id, $g['kategorie'], 'kategorie-tymu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // AKTUALITY (WordPress post)
    // Zdroj: fotbalunas.cz, slavojmyto.cz
    // ------------------------------------------------------------------
    $aktuality = array(
        array(
            'title'   => 'Postup do 7. ligy potvrzen',
            'content' => 'V nejvyšší okresní soutěži na Rokycansku se celé jaro odehrával souboj o první místo. To nakonec uhájila Raková před rezervou Mýta, čímž si tak zajistila postup. Smutnit ale nakonec nemusel ani Slavoj, který původně měl mít černého Petra. Postupové čachry znamenaly i pro něj návrat zpět do krajské soutěže.',
            'datum'   => '2025-06-15',
        ),
        array(
            'title'   => 'Starší žáci Mýta udrželi třikrát čisté konto',
            'content' => 'V sobotu 22.3.2025 na umělém trávníku mezi rokycanskými bytovkami sehráli turnaj mladších žáků okresního fotbalového svazu. O jejich svěřence se staral šéftrenér OTM Michal Šilhánek. Naši žáci předvedli výborné výkony a třikrát udrželi čisté konto.',
            'datum'   => '2025-03-23',
        ),
        array(
            'title'   => 'Mýto druhé v tabulce s 30 body',
            'content' => 'Po 16 odehraných kolech sezóny 2025/26 se TJ Slavoj Mýto drží na výborném 2. místě tabulky Plzeňského krajského přeboru s 30 body. Střelecky táhnou Lukáš Křivánek (22 gólů) a David Vaněček (20 gólů). Jarní část sezóny začíná 22. března domácím zápasem proti SK Slavia Vejprnice.',
            'datum'   => '2026-03-01',
        ),
        array(
            'title'   => 'Přípravný turnaj – Mýto vyhrálo skupinu',
            'content' => 'V březnu 2025 zvítězilo Mýto na přípravném turnaji – porazilo Holoubkov 1:0, Příkosice 3:0 a remizovalo 0:0 s Břasy. Skvělá příprava před jarem přinesla potřebné herní minuty a prověřila spolupráci hráčů.',
            'datum'   => '2025-03-10',
        ),
    );

    foreach ($aktuality as $a) {
        if (slavoj_post_exists($a['title'], 'post')) {
            continue;
        }
        $p_id = wp_insert_post(array(
            'post_title'    => $a['title'],
            'post_content'  => $a['content'],
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'post_date'     => $a['datum'] . ' 10:00:00',
        ));
        if ($p_id && !is_wp_error($p_id)) {
            // Přiřadit kategorii 'aktuality'
            wp_set_post_categories($p_id, array(
                get_cat_ID('aktuality') ?: wp_create_category('aktuality'),
            ));
            $created++;
        }
    }

    return $created;
}

// =====================================================================
// SEED – vytvoření WordPress stránek se šablonami tématu
// =====================================================================

/**
 * Vytvoří WordPress stránky (post_type = page) pro každou sekci webu
 * a přiřadí jim správnou šablonu tématu.
 *
 * Volat z administrace přes tlačítko „Vytvořit stránky webu".
 *
 * @return int Počet nově vytvořených stránek.
 */
function slavoj_cf_seed_pages() {
    $created = 0;

    /*
     * Definice stránek: title => template filename
     * Prázdný řetězec = výchozí šablona WordPress (page.php / front-page.php).
     * Poznámka: nastavení úvodní stránky (show_on_front) je záměrně vynecháno –
     * toto tlačítko pouze vytváří stránky a nepřepisuje vizuální nastavení webu.
     * Úvodní stránku nastavte ručně v Nastavení → Čtení.
     */
    $pages = array(
        array(
            'title'    => 'Domů',
            'template' => '', // front-page.php se načte automaticky pro front page
        ),
        array(
            'title'    => 'Zápasy',
            'template' => 'page-zapasy.php',
        ),
        array(
            'title'    => 'Týmy',
            'template' => 'page-tymy.php',
        ),
        array(
            'title'    => 'Galerie',
            'template' => '',
        ),
        array(
            'title'    => 'Sponzoři',
            'template' => 'page-sponzori.php',
        ),
        array(
            'title'    => 'Kontakty',
            'template' => 'page-kontakty.php',
        ),
        array(
            'title'    => 'Historie',
            'template' => 'page-historie.php',
        ),
    );

    foreach ($pages as $p) {
        if (slavoj_post_exists($p['title'], 'page')) {
            continue;
        }

        $page_id = wp_insert_post(array(
            'post_title'  => $p['title'],
            'post_type'   => 'page',
            'post_status' => 'publish',
        ));

        if ($page_id && ! is_wp_error($page_id)) {
            // Přiřadit šablonu tématu (prázdný řetězec = výchozí / page.php)
            if (!empty($p['template'])) {
                update_post_meta($page_id, '_wp_page_template', $p['template']);
            }

            $created++;
        }
    }

    return $created;
}

/**
 * Pomocná funkce – zkontroluje, zda příspěvek s daným titulem a typem již existuje.
 *
 * @param string $title     Titulek příspěvku.
 * @param string $post_type Typ příspěvku (CPT slug nebo 'post').
 * @return bool True pokud příspěvek již existuje.
 */
function slavoj_post_exists($title, $post_type) {
    $query = new WP_Query(array(
        'post_type'              => $post_type,
        'title'                  => $title,
        'post_status'            => 'any',
        'posts_per_page'         => 1,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ));
    return $query->have_posts();
}
