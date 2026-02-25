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
            <td>datum_zapasu, cas_zapasu, domaci, hoste, skore, strelci, misto_konani</td>
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
        'Muži A'        => 'muzi-a',
        'Muži B'        => 'muzi-b',
        'Dorost'        => 'dorost',
        'Starší žáci'   => 'starsi-zaci',
        'Mladší žáci'   => 'mladsi-zaci',
        'Přípravka'     => 'pripravka',
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
        echo esc_html(get_post_meta($post_id, 'tym_slug', true));
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
