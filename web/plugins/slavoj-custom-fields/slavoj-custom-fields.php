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
    // ZÁPASY (zapas) – Sezóna 2025/26, Muži A
    // ------------------------------------------------------------------
    $zapasy = array(
        array(
            'title'         => 'TJ Slavoj Mýto vs Rapid Plzeň',
            'domaci'        => 'Rapid Plzeň',
            'hoste'         => 'TJ Slavoj Mýto',
            'datum_zapasu'  => '2025-08-08',
            'cas_zapasu'    => '18:00',
            'skore'         => '4:2',
            'strelci'       => '2× Schmid, Bejček, Otec',
            'misto_konani'  => 'Hosté',
            'sezona'        => '2025/26',
            'kategorie'     => 'muzi-a',
            'stav'          => 'odehrany',
        ),
        array(
            'title'         => 'TJ Slavoj Mýto vs TJ Chotěšov',
            'domaci'        => 'TJ Slavoj Mýto',
            'hoste'         => 'TJ Chotěšov',
            'datum_zapasu'  => '2025-08-15',
            'cas_zapasu'    => '18:00',
            'skore'         => '',
            'strelci'       => '',
            'misto_konani'  => 'Domácí',
            'sezona'        => '2025/26',
            'kategorie'     => 'muzi-a',
            'stav'          => 'nadchazejici',
        ),
        array(
            'title'         => 'TJ Slavoj Mýto vs FK Bohemia Kaznějov',
            'domaci'        => 'FK Bohemia Kaznějov',
            'hoste'         => 'TJ Slavoj Mýto',
            'datum_zapasu'  => '2025-08-22',
            'cas_zapasu'    => '18:00',
            'skore'         => '',
            'strelci'       => '',
            'misto_konani'  => 'Hosté',
            'sezona'        => '2025/26',
            'kategorie'     => 'muzi-a',
            'stav'          => 'nadchazejici',
        ),
        array(
            'title'         => 'Rapid Plzeň vs TJ Slavoj Mýto (Muži B)',
            'domaci'        => 'Rapid Plzeň',
            'hoste'         => 'TJ Slavoj Mýto',
            'datum_zapasu'  => '2025-08-08',
            'cas_zapasu'    => '18:00',
            'skore'         => '',
            'strelci'       => '',
            'misto_konani'  => 'Hosté',
            'sezona'        => '2025/26',
            'kategorie'     => 'muzi-b',
            'stav'          => 'nadchazejici',
        ),
        array(
            'title'         => 'TJ Slavoj Mýto vs FK Bohemia Kaznějov (Dorost)',
            'domaci'        => 'TJ Slavoj Mýto',
            'hoste'         => 'FK Bohemia Kaznějov',
            'datum_zapasu'  => '2025-08-08',
            'cas_zapasu'    => '16:00',
            'skore'         => '',
            'strelci'       => '',
            'misto_konani'  => 'Domácí',
            'sezona'        => '2025/26',
            'kategorie'     => 'dorost',
            'stav'          => 'nadchazejici',
        ),
        array(
            'title'         => 'Rapid Plzeň vs TJ Slavoj Mýto (Starší žáci)',
            'domaci'        => 'Rapid Plzeň',
            'hoste'         => 'TJ Slavoj Mýto',
            'datum_zapasu'  => '2025-08-08',
            'cas_zapasu'    => '10:00',
            'skore'         => '',
            'strelci'       => '',
            'misto_konani'  => 'Hosté',
            'sezona'        => '2025/26',
            'kategorie'     => 'starsi-zaci',
            'stav'          => 'nadchazejici',
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
            update_post_meta($post_id, 'misto_konani', $z['misto_konani']);
            wp_set_object_terms($post_id, $z['sezona'],    'sezona');
            wp_set_object_terms($post_id, $z['kategorie'], 'kategorie-tymu');
            wp_set_object_terms($post_id, $z['stav'],      'stav-zapasu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // TÝM (tym) – Muži A, Sezóna 2024/25
    // ------------------------------------------------------------------
    $tym_title = 'Muži A';
    if (!slavoj_post_exists($tym_title, 'tym')) {
        $tym_id = wp_insert_post(array(
            'post_title'   => $tym_title,
            'post_type'    => 'tym',
            'post_status'  => 'publish',
        ));
        if ($tym_id && !is_wp_error($tym_id)) {
            update_post_meta($tym_id, 'tym_slug',          'muzi-a');
            update_post_meta($tym_id, 'pocet_hracu',       16);
            update_post_meta($tym_id, 'hlavni_trener',     'Nyklas Petr');
            update_post_meta($tym_id, 'asistent_trenera',  'Honzík Ivan');
            update_post_meta($tym_id, 'zdravotnik',        'Hrabák Jan');
            wp_set_object_terms($tym_id, '2024/25', 'sezona');
            wp_set_object_terms($tym_id, 'muzi-a',  'kategorie-tymu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // HRÁČI (hrac) – Muži A, Sezóna 2024/25
    // ------------------------------------------------------------------
    $hraci = array(
        array('title' => 'Josef Brankář',      'cislo' => 1,  'rok' => 1987, 'pozice' => 'brankari',    'tym' => 'muzi-a'),
        array('title' => 'Pavel Brankář',      'cislo' => 2,  'rok' => 1987, 'pozice' => 'brankari',    'tym' => 'muzi-a'),
        array('title' => 'Adam Bejček',        'cislo' => 4,  'rok' => 1989, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jan Novák',          'cislo' => 5,  'rok' => 1990, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Martin Procházka',   'cislo' => 6,  'rok' => 2000, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Tomáš Horáček',      'cislo' => 7,  'rok' => 2005, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Jakub Kříž',         'cislo' => 8,  'rok' => 1999, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Ondřej Schmid',      'cislo' => 10, 'rok' => 1995, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
        array('title' => 'Lukáš Otec',         'cislo' => 11, 'rok' => 1993, 'pozice' => 'hraci-v-poli','tym' => 'muzi-a'),
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
            wp_set_object_terms($hrac_id, '2024/25',   'sezona');
            wp_set_object_terms($hrac_id, $h['tym'],   'kategorie-tymu');
            wp_set_object_terms($hrac_id, $h['pozice'],'pozice-hrace');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // KONTAKTY (kontakt) – Výbor klubu
    // ------------------------------------------------------------------
    $kontakty = array(
        array('title' => 'Radek Koula',      'pozice' => 'Prezident klubu',                  'telefon' => '+420 602 224 684', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 1),
        array('title' => 'Milan Huml',       'pozice' => 'Sekretář klubu a administrativa',  'telefon' => '+420 737 259 684', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 2),
        array('title' => 'František Končel', 'pozice' => 'Pokladník',                        'telefon' => '',                 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 3),
        array('title' => 'Petr Bejček',      'pozice' => 'Člen výboru, trenér mládeže',      'telefon' => '+420 776 137 057', 'email' => 'tjslavojmyto@seznam.cz', 'poradi' => 4),
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
    // GALERIE (galerie)
    // ------------------------------------------------------------------
    $galerie = array(
        array('title' => 'Mýto vs Lhota',              'tym' => 'Muži A', 'kategorie' => 'muzi-a',    'sezona' => '2024/25'),
        array('title' => 'Horní Bříza vs Mýto',        'tym' => 'Muži A', 'kategorie' => 'muzi-a',    'sezona' => '2024/25'),
        array('title' => 'Turnaj mladších žáků – Rokycany', 'tym' => 'Mladší žáci', 'kategorie' => 'mladsi-zaci', 'sezona' => '2024/25'),
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
            update_post_meta($g_id, 'tym', $g['tym']);
            wp_set_object_terms($g_id, $g['sezona'],    'sezona');
            wp_set_object_terms($g_id, $g['kategorie'], 'kategorie-tymu');
            $created++;
        }
    }

    // ------------------------------------------------------------------
    // AKTUALITY (WordPress post) – příspěvky z homepage
    // ------------------------------------------------------------------
    $aktuality = array(
        array(
            'title'   => 'Postup do 7. ligy',
            'content' => 'V nejvyšší okresní soutěži na Rokycansku se celé jaro odehrával souboj o první místo. To nakonec uhájila Raková před rezervou Mýta, čímž si tak zajistila postup. Smutnit ale nakonec nemusel ani Slavoj, který původně měl mít černého Petra. Postupové čachry znamenaly i pro něj návrat zpět do krajské soutěže.',
        ),
        array(
            'title'   => 'Starší žáci Mýta udrželi třikrát čisté konto',
            'content' => 'V sobotu 22.3.2025 na umělém trávníku mezi rokycanskými bytovkami sehráli turnaj mladších žáků okresního fotbalového svazu. O jejich svěřence se staral šéftrenér OTM Michal Šilhánek.',
        ),
    );

    foreach ($aktuality as $a) {
        if (slavoj_post_exists($a['title'], 'post')) {
            continue;
        }
        $p_id = wp_insert_post(array(
            'post_title'   => $a['title'],
            'post_content' => $a['content'],
            'post_type'    => 'post',
            'post_status'  => 'publish',
        ));
        if ($p_id && !is_wp_error($p_id)) {
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
