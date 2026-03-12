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

      <h2>Ukázková data – sezóna 2025/26</h2>
      <p>Kliknutím na tlačítko níže naimportujete ukázková data ze sezóny 2025/26:
        týmy <strong>TJ Slavoj Mýto (Muži A)</strong> a <strong>TJ Slavoj Mýto B (Muži B)</strong>,
        15 zápasů Krajského přeboru, 13 zápasů 1.B třídy sk. C a soupisku 7 hráčů Mužů B.
        Seed lze spustit opakovaně – duplicitní záznamy jsou automaticky přeskočeny.
      </p>
      <form method="post">
        <?php wp_nonce_field('slavoj_seed_demo', 'slavoj_seed_demo_nonce'); ?>
        <input type="hidden" name="slavoj_action" value="seed_demo">
        <?php submit_button('Vytvořit ukázková data (sezóna 2025/26)', 'secondary', 'slavoj_seed_demo_submit'); ?>
      </form>

      <?php
      if (isset($_POST['slavoj_action']) && $_POST['slavoj_action'] === 'seed_demo') {
          if (check_admin_referer('slavoj_seed_demo', 'slavoj_seed_demo_nonce')) {
              $result = slavoj_cf_seed_demo_data();
              echo '<div class="notice notice-success"><p>✅ Ukázková data byla úspěšně vytvořena! ';
              echo esc_html("Vytvořeno: {$result['tymy']} tým/ů, {$result['zapasy']} zápas/ů, {$result['hraci']} hráč/ů.");
              echo '</p></div>';
          }
      }
      ?>

      <hr style="margin:30px 0;">

      <!-- ================================================================
           IMPORT GALERIE ZE STARÉHO WEBU
           ================================================================ -->
      <h2>Import galerie ze starého webu</h2>
      <p>Nástroj pro stažení fotoalb z <strong>slavojmyto.cz</strong> a jejich vložení do WordPress galerie.</p>

      <?php if (get_option('slavoj_galerie_import_done')): ?>
        <div class="notice notice-success inline"><p>✅ Import byl již spuštěn. Lze spustit znovu – duplicitní alba jsou přeskočena.</p></div>
      <?php endif; ?>

      <?php
      $helper_files = array_filter(array(
          ABSPATH . 'import-galerie.php',
          ABSPATH . 'fix-galerie.php',
          ABSPATH . 'fix-galerie.sql',
      ), 'file_exists');
      $kategorie_terms = get_terms(array('taxonomy' => 'kategorie-tymu', 'hide_empty' => false));
      $sezona_terms    = get_terms(array('taxonomy' => 'sezona',          'hide_empty' => false));
      ?>

      <div style="display:flex;gap:20px;flex-wrap:wrap;margin-top:16px;">

        <!-- Krok 1 – Import -->
        <div class="card" style="padding:20px;min-width:320px;flex:1;">
          <h3 style="margin-top:0;">Krok 1 – Stáhnout alba</h3>
          <table class="form-table" style="margin:0;">
            <tr>
              <th style="width:100px;padding:8px 0;"><label for="sg-url">URL galerie</label></th>
              <td style="padding:8px 0;">
                <input type="url" id="sg-url" class="regular-text"
                       value="http://www.slavojmyto.cz/cs/galerie/muzi">
              </td>
            </tr>
            <tr>
              <th style="padding:8px 0;"><label for="sg-sezona">Sezóna</label></th>
              <td style="padding:8px 0;">
                <select id="sg-sezona" style="margin-right:6px;">
                  <option value="">— nová —</option>
                  <?php foreach ($sezona_terms as $t): ?>
                    <option value="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></option>
                  <?php endforeach; ?>
                </select>
                nebo: <input type="text" id="sg-sezona-nova" placeholder="např. 2024/25" style="width:90px;">
              </td>
            </tr>
            <tr>
              <th style="padding:8px 0;"><label for="sg-kat">Kategorie</label></th>
              <td style="padding:8px 0;">
                <select id="sg-kat">
                  <option value="">— bez kategorie —</option>
                  <?php foreach ($kategorie_terms as $t): ?>
                    <option value="<?php echo esc_attr($t->slug); ?>"
                      <?php selected($t->slug, 'muzi-a'); ?>><?php echo esc_html($t->name); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
            </tr>
          </table>
          <p style="margin-top:14px;">
            <button id="sg-btn-import" class="button button-primary button-large">▶ Spustit import</button>
          </p>
        </div>

        <!-- Krok 2 – Oprava -->
        <div class="card" style="padding:20px;min-width:240px;flex:0 0 auto;">
          <h3 style="margin-top:0;">Krok 2 – Opravit data</h3>
          <p style="color:#666;">Odstraní logo ze seznamu fotek,<br>opraví zdvojené roky v názvech.</p>
          <button id="sg-btn-fix" class="button button-secondary button-large">🔧 Opravit data</button>
        </div>

        <!-- Krok 3 – Smazání starých souborů -->
        <?php if ($helper_files): ?>
        <div class="card" style="padding:20px;min-width:240px;flex:0 0 auto;border-left:4px solid #d63638;">
          <h3 style="margin-top:0;">Krok 3 – Smazat soubory</h3>
          <p style="color:#666;">Staré importní skripty v kořeni WP:</p>
          <ul style="margin:0 0 12px 18px;">
            <?php foreach ($helper_files as $f): ?>
              <li><code><?php echo esc_html(basename($f)); ?></code></li>
            <?php endforeach; ?>
          </ul>
          <button id="sg-btn-delete" class="button button-large"
                  style="border-color:#d63638;color:#d63638;">🗑 Smazat soubory</button>
        </div>
        <?php endif; ?>

      </div><!-- /flex -->

      <!-- Log výstupu -->
      <div id="sg-log-wrap" style="display:none;margin-top:20px;max-width:900px;">
        <h4>Průběh</h4>
        <div id="sg-log"
             style="background:#1d2327;color:#a7aaad;font-family:monospace;font-size:12px;
                    padding:14px;border-radius:4px;height:320px;overflow-y:auto;white-space:pre-wrap;">
        </div>
        <button id="sg-btn-log-clear" class="button" style="margin-top:8px;">Vymazat log</button>
      </div>

      <script>
      (function($) {
        var $log = $('#sg-log'), $wrap = $('#sg-log-wrap');
        var nonce = <?php echo wp_json_encode(wp_create_nonce('slavoj_galerie_nonce')); ?>;

        function log(txt) {
          $wrap.show();
          $log.append(txt + "\n");
          $log.scrollTop($log[0].scrollHeight);
        }

        function busy(btn, on) {
          if (on) btn.prop('disabled', true).data('orig', btn.html()).html('⏳ Probíhá…');
          else    btn.prop('disabled', false).html(btn.data('orig') || btn.html());
        }

        function call(action, extra, btn) {
          busy(btn, true);
          log('--- ' + new Date().toLocaleTimeString('cs') + ' – ' + action + ' ---');
          $.ajax({
            url: ajaxurl, method: 'POST', timeout: 300000,
            data: $.extend({ action: action, nonce: nonce }, extra),
            success: function(r) {
              if (r.success && r.data && r.data.log) log(r.data.log);
              else if (r.data && r.data.message)    log('❌ ' + r.data.message);
              if (action === 'slavoj_galerie_delete') location.reload();
            },
            error:    function(x) { log('❌ AJAX chyba: ' + x.status + ' ' + x.statusText); },
            complete: function()  { busy(btn, false); },
          });
        }

        $('#sg-btn-import').on('click', function() {
          var s = $('#sg-sezona').val() || $('#sg-sezona-nova').val();
          if (!s) { alert('Zadej sezónu.'); return; }
          call('slavoj_galerie_import', {
            url: $('#sg-url').val(), sezona: s, kategorie: $('#sg-kat').val(),
          }, $(this));
        });

        $('#sg-btn-fix').on('click', function() {
          call('slavoj_galerie_fix', {}, $(this));
        });

        $('#sg-btn-delete').on('click', function() {
          if (!confirm('Smazat pomocné soubory?')) return;
          call('slavoj_galerie_delete', {}, $(this));
        });

        $('#sg-btn-log-clear').on('click', function() { $log.text(''); });
      })(jQuery);
      </script>

      <hr style="margin:30px 0;">
      <h2>Nápověda</h2>
      <ol>
        <li>Přejděte do menu <strong>Zápasy → Přidat zápas</strong> a vyplňte detaily zápasu.</li>
        <li>V postranním panelu přiřaďte <strong>Kategorii týmu</strong> (Muži A, Muži B…) a <strong>Sezónu</strong>.</li>
        <li>Pro zobrazení hráčů v soupisce přidejte hráče přes <strong>Hráči → Přidat hráče</strong> a v poli „Slug týmu" zadejte slug odpovídající kategorie (např. <code>muzi-a</code>).</li>
        <li>Galerie fotek – použij tlačítko <strong>▶ Spustit import</strong> výše pro automatické stažení fotek ze starého webu.</li>
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
// SEED – ukázková data sezóna 2025/26
// =====================================================================

/**
 * Vytvoří ukázková data sezóny 2025/26: týmy, zápasy a hráče.
 * Funkci lze spustit opakovaně – duplicitní záznamy jsou přeskočeny
 * (kontrola podle post_title + post_type).
 *
 * @return array  Počty vytvořených záznamů: tymy, zapasy, hraci.
 */
function slavoj_cf_seed_demo_data() {
    slavoj_cf_seed_taxonomies();

    // Zajistíme existenci taxonomie „Neuvedeno" pro pozici hráče.
    if (!term_exists('neuvedeno', 'pozice-hrace')) {
        wp_insert_term('Neuvedeno', 'pozice-hrace', array('slug' => 'neuvedeno'));
    }

    $counts = array('tymy' => 0, 'zapasy' => 0, 'hraci' => 0);

    // ------------------------------------------------------------------
    // Pomocná funkce: vytvoří post pokud ještě neexistuje.
    // ------------------------------------------------------------------
    $create_post = function ($args, $meta, $terms) use (&$counts) {
        $existing = get_posts(array(
            'post_type'   => $args['post_type'],
            'title'       => $args['post_title'],
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields'      => 'ids',
        ));
        if (!empty($existing)) {
            return $existing[0];
        }
        $post_id = wp_insert_post(array_merge(array('post_status' => 'publish'), $args));
        if (is_wp_error($post_id) || !$post_id) {
            return 0;
        }
        foreach ($meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }
        foreach ($terms as $taxonomy => $slugs) {
            wp_set_post_terms($post_id, $slugs, $taxonomy, false);
        }
        $counts[$args['post_type'] === 'zapas' ? 'zapasy' : ($args['post_type'] === 'tym' ? 'tymy' : 'hraci')]++;
        return $post_id;
    };

    // ------------------------------------------------------------------
    // TÝMY
    // ------------------------------------------------------------------
    $create_post(
        array('post_type' => 'tym', 'post_title' => 'TJ Slavoj Mýto – Muži A',
              'post_content' => 'Plzeňský krajský přebor – sezóna 2025/26'),
        array('tym_slug' => 'muzi-a'),
        array('sezona' => array('2025-26'), 'kategorie-tymu' => array('muzi-a'))
    );
    $create_post(
        array('post_type' => 'tym', 'post_title' => 'TJ Slavoj Mýto B – Muži B',
              'post_content' => 'Plzeňský 1.B třída sk. C – sezóna 2025/26'),
        array('tym_slug' => 'muzi-b'),
        array('sezona' => array('2025-26'), 'kategorie-tymu' => array('muzi-b'))
    );

    // ------------------------------------------------------------------
    // ZÁPASY – Muži A (Krajský přebor, sezóna 2025/26)
    // ------------------------------------------------------------------
    $zapasy_a = array(
        array('FK Rapid Plzeň vs TJ Slavoj Mýto',       'FK Rapid Plzeň',        'TJ Slavoj Mýto',        '2025-08-08', '18:00', '2:4'),
        array('TJ Slavoj Mýto vs SK Nepomuk',            'TJ Slavoj Mýto',        'SK Nepomuk',            '2025-08-17', '17:30', '4:0'),
        array('TJ Vejprnice vs TJ Slavoj Mýto',          'TJ Vejprnice',          'TJ Slavoj Mýto',        '2025-08-23', '10:15', '3:2'),
        array('TJ Slavoj Mýto vs FK Měcholupy',          'TJ Slavoj Mýto',        'FK Měcholupy',          '2025-08-30', '17:30', '5:2'),
        array('FK Horní Bříza vs TJ Slavoj Mýto',        'FK Horní Bříza',        'TJ Slavoj Mýto',        '2025-09-06', '10:15', '3:0'),
        array('FK Lhota vs TJ Slavoj Mýto',              'FK Lhota',              'TJ Slavoj Mýto',        '2025-09-13', '17:00', '4:0'),
        array('TJ Slavoj Mýto vs TJ Horšovský Týn',      'TJ Slavoj Mýto',        'TJ Horšovský Týn',      '2025-09-21', '16:45', '3:2'),
        array('TJ Chotěšov vs TJ Slavoj Mýto',           'TJ Chotěšov',           'TJ Slavoj Mýto',        '2025-09-27', '10:15', '2:2'),
        array('TJ Slavoj Mýto vs FK Chlumčany',          'TJ Slavoj Mýto',        'FK Chlumčany',          '2025-10-04', '16:00', '3:2'),
        array('FK Koloveč vs TJ Slavoj Mýto',            'FK Koloveč',            'TJ Slavoj Mýto',        '2025-10-12', '16:00', '0:1'),
        array('TJ Slavoj Mýto vs FK Bohemia Kaznějov',   'TJ Slavoj Mýto',        'FK Bohemia Kaznějov',   '2025-10-18', '15:30', '2:1'),
        array('FK Radnice vs TJ Slavoj Mýto',            'FK Radnice',            'TJ Slavoj Mýto',        '2025-10-26', '14:30', '0:6'),
        array('TJ Slavoj Mýto vs FK Nýrsko',             'TJ Slavoj Mýto',        'FK Nýrsko',             '2025-11-01', '14:00', '0:1'),
        array('FK Holýšov vs TJ Slavoj Mýto',            'FK Holýšov',            'TJ Slavoj Mýto',        '2025-11-09', '14:00', '0:2'),
        array('TJ Slavoj Mýto vs FK Luby',               'TJ Slavoj Mýto',        'FK Luby',               '2025-11-15', '13:30', '1:1'),
    );
    foreach ($zapasy_a as $z) {
        $create_post(
            array('post_type' => 'zapas', 'post_title' => $z[0], 'post_date' => $z[3] . ' ' . $z[4] . ':00'),
            array('datum_zapasu' => $z[3], 'cas_zapasu' => $z[4], 'domaci' => $z[1], 'hoste' => $z[2], 'skore' => $z[5]),
            array('sezona' => array('2025-26'), 'kategorie-tymu' => array('muzi-a'), 'stav-zapasu' => array('odehrany'))
        );
    }

    // ------------------------------------------------------------------
    // ZÁPASY – Muži B (1.B třída sk. C, sezóna 2025/26)
    // ------------------------------------------------------------------
    $zapasy_b = array(
        array('TJ Slavoj Mýto B vs FK Úněšov',          'TJ Slavoj Mýto B',      'FK Úněšov',             '2025-08-24', '17:30', '4:3'),
        array('FK Všeruby vs TJ Slavoj Mýto B',          'FK Všeruby',            'TJ Slavoj Mýto B',      '2025-08-27', '18:00', '2:2'),
        array('FK Ledce vs TJ Slavoj Mýto B',            'FK Ledce',              'TJ Slavoj Mýto B',      '2025-08-31', '13:30', '6:0'),
        array('TJ Slavoj Mýto B vs FK Touškov',          'TJ Slavoj Mýto B',      'FK Touškov',            '2025-09-07', '17:00', '1:3'),
        array('TJ Zbiroh vs TJ Slavoj Mýto B',           'TJ Zbiroh',             'TJ Slavoj Mýto B',      '2025-09-13', '17:00', '7:2'),
        array('TJ Slavoj Mýto B vs FK Plasy',            'TJ Slavoj Mýto B',      'FK Plasy',              '2025-09-21', '10:00', '1:2'),
        array('TJ Slavoj Mýto B vs FK Raková',           'TJ Slavoj Mýto B',      'FK Raková',             '2025-09-27', '10:15', '0:1'),
        array('FK Doubravka B vs TJ Slavoj Mýto B',      'FK Doubravka B',        'TJ Slavoj Mýto B',      '2025-10-05', '16:00', '5:2'),
        array('TJ Slavoj Mýto B vs FK Volduchy',         'TJ Slavoj Mýto B',      'FK Volduchy',           '2025-10-11', '10:00', '5:2'),
        array('FK Rapid Plzeň B vs TJ Slavoj Mýto B',    'FK Rapid Plzeň B',      'TJ Slavoj Mýto B',      '2025-10-19', '15:30', '7:2'),
        array('TJ Slavoj Mýto B vs FK Horní Bříza B',    'TJ Slavoj Mýto B',      'FK Horní Bříza B',      '2025-10-25', '10:15', '2:4'),
        array('FK Bolevec vs TJ Slavoj Mýto B',          'FK Bolevec',            'TJ Slavoj Mýto B',      '2025-11-01', '14:00', '3:0'),
        array('TJ Slavoj Mýto B vs FK Příkosice',        'TJ Slavoj Mýto B',      'FK Příkosice',          '2025-11-08', '10:00', '3:2'),
    );
    foreach ($zapasy_b as $z) {
        $create_post(
            array('post_type' => 'zapas', 'post_title' => $z[0], 'post_date' => $z[3] . ' ' . $z[4] . ':00'),
            array('datum_zapasu' => $z[3], 'cas_zapasu' => $z[4], 'domaci' => $z[1], 'hoste' => $z[2], 'skore' => $z[5]),
            array('sezona' => array('2025-26'), 'kategorie-tymu' => array('muzi-b'), 'stav-zapasu' => array('odehrany'))
        );
    }

    // ------------------------------------------------------------------
    // HRÁČI – soupiska TJ Slavoj Mýto B, sezóna 2025/26
    // Format: cislo, jmeno
    // ------------------------------------------------------------------
    $hraci = array(
        array(1,  'Milan Navrátil'),
        array(6,  'Marek Šobáň'),
        array(8,  'Martin Drábek'),
        array(9,  'Alexandr Čajkovskij'),
        array(10, 'Matěj Tůma'),
        // Obě čísla 13 jsou záměrná – zdroj (slavojmyto.cz) uvádí #13 pro oba hráče.
        array(13, 'Jiří Drábek'),
        array(13, 'Filip Stejskal'),
    );
    foreach ($hraci as $h) {
        $create_post(
            array('post_type' => 'hrac', 'post_title' => $h[1]),
            array('cislo' => $h[0], 'tym_slug' => 'muzi-b'),
            array('sezona' => array('2025-26'), 'kategorie-tymu' => array('muzi-b'), 'pozice-hrace' => array('neuvedeno'))
        );
    }

    return $counts;
}

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
// IMPORT GALERIE – AJAX handlery
// =====================================================================

add_action('wp_ajax_slavoj_galerie_import', 'slavoj_galerie_import_handler');
function slavoj_galerie_import_handler() {
    check_ajax_referer('slavoj_galerie_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error(array('message' => 'Nedostatečná oprávnění.'));

    set_time_limit(0);
    ini_set('memory_limit', '512M');

    $base_url  = 'http://www.slavojmyto.cz';
    $gal_url   = sanitize_text_field(wp_unslash($_POST['url'] ?? $base_url . '/cs/galerie/muzi'));
    $sezona    = sanitize_text_field(wp_unslash($_POST['sezona'] ?? ''));
    $kategorie = sanitize_text_field(wp_unslash($_POST['kategorie'] ?? ''));

    $log = '';
    $L   = function ($m) use (&$log) { $log .= '[' . date('H:i:s') . '] ' . $m . "\n"; };

    $L('=== Import galerie spuštěn ===');
    $L("URL: $gal_url | Sezóna: $sezona | Kategorie: $kategorie");

    // Sezóna
    $sezona_id = null;
    if ($sezona) {
        $res = wp_insert_term($sezona, 'sezona', array('slug' => sanitize_title($sezona)));
        $sezona_id = is_wp_error($res) ? ($res->error_data['term_exists'] ?? null) : $res['term_id'];
        $L("Sezóna ID: $sezona_id");
    }

    // Kategorie
    $kat_id = null;
    if ($kategorie) {
        $t = get_term_by('slug', $kategorie, 'kategorie-tymu');
        $kat_id = $t ? $t->term_id : null;
    }

    // Upload složka
    $upload_dir  = wp_upload_dir();
    $target_base = $upload_dir['basedir'] . '/galerie-import';
    wp_mkdir_p($target_base);

    // Hlavní stránka galerie
    $html = slavoj_galerie_fetch($gal_url);
    if (!$html) { wp_send_json_success(array('log' => $log . "CHYBA: Nepodařilo se načíst stránku.\n")); }

    $doc = slavoj_galerie_parse($html);
    $xp  = new DOMXPath($doc);

    // Najdi alba – <a href="...galerie...">
    $albums = array();
    foreach ($xp->query('//a[contains(@href, "galerie")]') as $link) {
        $href  = trim($link->getAttribute('href'));
        $title = trim($link->textContent);
        if (!$href || $href === '#' || strlen($title) < 3) continue;
        if (preg_match('#/galerie(/[a-z-]*)?$#i', $href)) continue;

        $abs = slavoj_galerie_absolute($base_url, $href);
        if (!isset($albums[$abs])) {
            $albums[$abs] = $title;
            $L("  Album: $title → $abs");
        }
    }
    $L('Nalezeno alb: ' . count($albums));

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $imported = 0;
    foreach ($albums as $album_url => $album_title) {
        $L("--- $album_title ---");

        // Přeskočit pokud existuje
        $ex = get_posts(array('post_type' => 'galerie', 'title' => $album_title, 'numberposts' => 1, 'post_status' => 'any'));
        if ($ex) { $L("  Přeskočeno (existuje)."); continue; }

        $post_id = wp_insert_post(array(
            'post_type'   => 'galerie',
            'post_title'  => $album_title,
            'post_status' => 'publish',
            'post_author' => 1,
        ));
        if (is_wp_error($post_id)) { $L("  CHYBA: " . $post_id->get_error_message()); continue; }
        $L("  Post ID: $post_id");

        if ($sezona_id) wp_set_object_terms($post_id, array((int)$sezona_id), 'sezona');
        if ($kat_id)    wp_set_object_terms($post_id, array((int)$kat_id),    'kategorie-tymu');

        // Načti stránku alba
        $a_html = slavoj_galerie_fetch($album_url);
        if (!$a_html) { $L("  CHYBA: Nepodařilo se načíst album."); continue; }

        $adoc = slavoj_galerie_parse($a_html);
        $axp  = new DOMXPath($adoc);

        $img_nodes = null;
        $selectors = array(
            '//div[contains(@class,"gallery")]//img',
            '//div[contains(@class,"phocagallery")]//img',
            '//div[contains(@class,"sigallery")]//img',
            '//a[contains(@href,".jpg") or contains(@href,".jpeg") or contains(@href,".png")]',
            '//img[contains(@src,"galeri") or contains(@src,"photo") or contains(@src,"images")]',
        );
        foreach ($selectors as $sel) {
            $nodes = $axp->query($sel);
            if ($nodes && $nodes->length > 0) { $img_nodes = $nodes; $L("  Selektor: $sel ({$nodes->length})"); break; }
        }

        if (!$img_nodes || $img_nodes->length === 0) { $L("  Žádné obrázky."); continue; }

        $album_dir = $target_base . '/' . sanitize_file_name(sanitize_title($album_title));
        wp_mkdir_p($album_dir);

        $att_ids = array();
        foreach ($img_nodes as $node) {
            $src = $node->nodeName === 'a'
                ? $node->getAttribute('href')
                : ($node->getAttribute('data-src') ?: $node->getAttribute('src'));
            if (!$src || strpos($src, 'data:') === 0) continue;

            $full_src = $node->getAttribute('data-full') ?: $node->getAttribute('data-original') ?: '';
            $img_url  = slavoj_galerie_absolute($base_url, $full_src ?: $src);

            list($path, $msg) = slavoj_galerie_download($img_url, $album_dir);
            $L("  $msg");
            if (!$path) continue;

            $att_id = slavoj_galerie_register($path, $post_id, $album_title);
            if ($att_id) { $att_ids[] = $att_id; $L("    ↳ Attachment ID: $att_id"); }
        }

        if (!empty($att_ids)) {
            update_post_meta($post_id, 'galerie_fotky', $att_ids);
            set_post_thumbnail($post_id, $att_ids[0]);
        }

        $imported++;
        $L("  Dokončeno. Fotek: " . count($att_ids));
    }

    update_option('slavoj_galerie_import_done', true);
    $L("=== Hotovo. Importováno: $imported alb. ===");
    wp_send_json_success(array('log' => $log));
}

add_action('wp_ajax_slavoj_galerie_fix', 'slavoj_galerie_fix_handler');
function slavoj_galerie_fix_handler() {
    check_ajax_referer('slavoj_galerie_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error(array('message' => 'Nedostatečná oprávnění.'));

    $log = '';
    $L   = function ($m) use (&$log) { $log .= '[' . date('H:i:s') . '] ' . $m . "\n"; };
    $L('=== Oprava dat galerie ===');

    // 1. Najdi logo attachmenty
    $logo_ids = array();
    $logo_posts = get_posts(array('post_type' => 'attachment', 'posts_per_page' => -1, 's' => 'logo-tjslavoj'));
    foreach ($logo_posts as $p) {
        $file = get_post_meta($p->ID, '_wp_attached_file', true);
        if (strpos($file, 'logo-tjslavoj') !== false) {
            $logo_ids[] = $p->ID;
            $L("Logo attachment: ID {$p->ID}");
        }
    }

    if (empty($logo_ids)) {
        $L('Žádná loga k odstranění.');
    } else {
        foreach ($logo_ids as $lid) { wp_delete_attachment($lid, true); $L("Smazán: $lid"); }
    }

    // 2. Vyčisti postmeta galerií
    $galerie = get_posts(array('post_type' => 'galerie', 'posts_per_page' => -1, 'post_status' => 'any'));
    foreach ($galerie as $post) {
        $ids = get_post_meta($post->ID, 'galerie_fotky', true);
        if (!is_array($ids)) continue;

        $cleaned = array_values(array_filter($ids, function($id) use ($logo_ids) {
            return !in_array($id, $logo_ids, true);
        }));
        if (count($cleaned) !== count($ids)) {
            update_post_meta($post->ID, 'galerie_fotky', $cleaned);
            $L("Album '{$post->post_title}': odstraněno " . (count($ids) - count($cleaned)) . " logo(s).");
            if (!empty($cleaned)) set_post_thumbnail($post->ID, $cleaned[0]);
            else                  delete_post_thumbnail($post->ID);
        }

        // Oprav zdvojené roky v názvech: "Muži - 2025 2025" → "Muži - 2025"
        $fixed = preg_replace('/(\d{4})\s+\1(\s*)$/', '$1$2', $post->post_title);
        if ($fixed !== $post->post_title) {
            wp_update_post(array('ID' => $post->ID, 'post_title' => $fixed));
            $L("Název: '{$post->post_title}' → '$fixed'");
        }
    }

    $L('=== Oprava dokončena. ===');
    wp_send_json_success(array('log' => $log));
}

add_action('wp_ajax_slavoj_galerie_delete', 'slavoj_galerie_delete_handler');
function slavoj_galerie_delete_handler() {
    check_ajax_referer('slavoj_galerie_nonce', 'nonce');
    if (!current_user_can('manage_options')) wp_send_json_error(array('message' => 'Nedostatečná oprávnění.'));

    $log = '';
    $L   = function ($m) use (&$log) { $log .= '[' . date('H:i:s') . '] ' . $m . "\n"; };
    foreach (array('import-galerie.php', 'fix-galerie.php', 'fix-galerie.sql') as $f) {
        $path = ABSPATH . $f;
        if (file_exists($path)) { unlink($path); $L("Smazán: $f"); }
        else                    { $L("Neexistuje: $f"); }
    }
    $L('Hotovo.');
    wp_send_json_success(array('log' => $log));
}

// =====================================================================
// IMPORT GALERIE – pomocné funkce
// =====================================================================

function slavoj_galerie_fetch($url) {
    $ctx = stream_context_create(array('http' => array(
        'timeout'         => 20,
        'follow_location' => true,
        'user_agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120',
        'header'          => "Accept-Language: cs,en;q=0.9\r\n",
    )));
    return @file_get_contents($url, false, $ctx) ?: null;
}

function slavoj_galerie_parse($html) {
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
    libxml_clear_errors();
    return $doc;
}

function slavoj_galerie_absolute($base, $href) {
    if (strpos($href, 'http') === 0)  return $href;
    if (strpos($href, '//') === 0)    return 'http:' . $href;
    if (strpos($href, '/') === 0)     return rtrim($base, '/') . $href;
    return rtrim($base, '/') . '/' . ltrim($href, '/');
}

function slavoj_galerie_download($url, $dir) {
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '-', basename(parse_url($url, PHP_URL_PATH)));
    $dest     = trailingslashit($dir) . $filename;
    if (file_exists($dest)) return array($dest, "Přeskočena (existuje): $filename");

    $ctx  = stream_context_create(array('http' => array('timeout' => 30, 'user_agent' => 'Mozilla/5.0')));
    $data = @file_get_contents($url, false, $ctx);
    if (!$data) return array(null, "CHYBA stahování: $url");

    file_put_contents($dest, $data);
    return array($dest, 'Staženo: ' . $filename . ' (' . round(strlen($data) / 1024) . ' KB)');
}

function slavoj_galerie_register($file_path, $post_id, $alt = '') {
    $upload_dir = wp_upload_dir();
    $rel = str_replace(
        wp_normalize_path(trailingslashit($upload_dir['basedir'])),
        '',
        wp_normalize_path($file_path)
    );

    $existing = get_posts(array('post_type' => 'attachment', 'meta_key' => '_wp_attached_file', 'meta_value' => $rel, 'numberposts' => 1));
    if ($existing) return $existing[0]->ID;

    $filetype = wp_check_filetype($file_path);
    $att_id   = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($file_path, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
        'post_parent'    => $post_id,
    ), $file_path, $post_id);

    if (is_wp_error($att_id)) return null;
    wp_update_attachment_metadata($att_id, wp_generate_attachment_metadata($att_id, $file_path));
    if ($alt) update_post_meta($att_id, '_wp_attachment_image_alt', sanitize_text_field($alt));
    return $att_id;
}
