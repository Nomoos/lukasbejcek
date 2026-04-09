<?php
/**
 * single-zapas.php — DETAIL JEDNOHO ZÁPASU
 * ===========================================
 * WordPress použije na URL: /zapas/nazev-zapasu/
 * (single-{post_type}.php pro CPT 'zapas')
 *
 * NEJKOMPLEXNĚJŠÍ ŠABLONA — obsahuje:
 * - Hlavičku zápasu (domácí vs hosté, skóre)
 * - Detail (datum, čas, střelci, popis)
 * - Správcovský formulář (editace skóre z frontendu!)
 * - wp_nonce_field() — ochrana proti CSRF útokům
 * - current_user_can() — oprávnění uživatele
 */

get_header();

while (have_posts()) : the_post();

    // Načtení VŠECH meta polí zápasu z databáze
    $datum   = get_post_meta(get_the_ID(), 'datum_zapasu', true);
    $cas     = get_post_meta(get_the_ID(), 'cas_zapasu', true);
    $domaci  = get_post_meta(get_the_ID(), 'domaci', true);
    $hoste   = get_post_meta(get_the_ID(), 'hoste', true);
    $skore   = get_post_meta(get_the_ID(), 'skore', true);
    $strelci = get_post_meta(get_the_ID(), 'strelci', true);

    // Načtení taxonomií přiřazených k zápasu
    $kategorie_tymu = get_the_terms(get_the_ID(), 'kategorie-tymu');
    $sezony         = get_the_terms(get_the_ID(), 'sezona');
    $stav_terms     = get_the_terms(get_the_ID(), 'stav-zapasu');

    /**
     * Bezpečné čtení názvu termu z pole.
     * get_the_terms() může vrátit: pole termů | false | WP_Error
     * → VŽDY musíme kontrolovat !is_wp_error() && neprázdné pole
     *
     * [0]->name = název prvního (a obvykle jediného) termu
     */
    $nazev_tymu  = (!is_wp_error($kategorie_tymu) && $kategorie_tymu) ? $kategorie_tymu[0]->name : '';
    $nazev_sez   = (!is_wp_error($sezony) && $sezony) ? $sezony[0]->name : '';
    $stav_zapasu = (!is_wp_error($stav_terms) && $stav_terms) ? $stav_terms[0]->name : ($skore ? 'Odehraný' : 'Nadcházející');

    /**
     * Formátování data přes PHP třídu DateTime.
     * DateTime::createFromFormat('Y-m-d', '2026-04-09')
     *   = vytvoří objekt z řetězce ve formátu rok-měsíc-den
     * ->format('j. n. Y') = převede na český formát "9. 4. 2026"
     *
     * POZN: jiný přístup než v archive-zapas.php (tam strtotime + date_i18n)
     */
    $datum_format = '';
    if ($datum) {
        $dt = DateTime::createFromFormat('Y-m-d', $datum);
        $datum_format = $dt ? $dt->format('j. n. Y') : $datum;
    }
?>

<div class="container py-5">

  <!-- Navigace zpět -->
  <div class="mb-4">
    <a href="<?php echo esc_url(get_post_type_archive_link('zapas')); ?>" class="btn btn-outline-secondary btn-sm">
      &larr; Všechny zápasy
    </a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8">

      <!-- Hlavička zápasu -->
      <div class="card shadow-sm mb-4">
        <div class="card-body text-center py-5">

          <?php if ($nazev_tymu || $nazev_sez) : ?>
            <p class="text-muted mb-3">
              <?php echo esc_html($nazev_tymu); ?>
              <?php if ($nazev_tymu && $nazev_sez) echo ' &bull; '; ?>
              <?php echo esc_html($nazev_sez); ?>
            </p>
          <?php endif; ?>

          <div class="d-flex justify-content-center align-items-center gap-4 mb-3">
            <div class="text-center">
              <h3 class="fw-bold mb-0"><?php echo esc_html($domaci ?: '—'); ?></h3>
              <small class="text-muted">Domácí</small>
            </div>

            <div class="text-center px-4">
              <span class="display-4 fw-bold text-primary">
                <?php echo $skore ? esc_html($skore) : 'vs'; ?>
              </span>
              <?php if ($datum_format || $cas) : ?>
                <p class="text-muted mb-0 mt-1 small">
                  <?php echo esc_html($datum_format); ?>
                  <?php echo $cas ? ' v ' . esc_html($cas) : ''; ?>
                </p>
              <?php endif; ?>
            </div>

            <div class="text-center">
              <h3 class="fw-bold mb-0"><?php echo esc_html($hoste ?: '—'); ?></h3>
              <small class="text-muted">Hosté</small>
            </div>
          </div>

          <!-- Badge stav zápasu -->
          <span class="badge <?php echo $skore ? 'bg-success' : 'bg-secondary'; ?>">
            <?php echo esc_html($stav_zapasu); ?>
          </span>
        </div>
      </div>

      <!-- Detaily -->
      <div class="row g-3 mb-4">
        <?php if ($datum_format) : ?>
          <div class="col-md-4">
            <div class="border rounded p-3 text-center">
              <div class="text-muted small">Datum</div>
              <strong><?php echo esc_html($datum_format); ?></strong>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($cas) : ?>
          <div class="col-md-4">
            <div class="border rounded p-3 text-center">
              <div class="text-muted small">Čas výkopu</div>
              <strong><?php echo esc_html($cas); ?></strong>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Střelci -->
      <?php if ($strelci) : ?>
        <div class="border rounded p-3 mb-4">
          <h5 class="fw-bold mb-2">Střelci</h5>
          <p class="mb-0"><?php echo esc_html($strelci); ?></p>
        </div>
      <?php endif; ?>

      <!-- Obsah příspěvku (poznámky, popis) -->
      <?php
      $content = get_the_content();
      if ($content) :
      ?>
        <div class="border rounded p-3 mb-4">
          <h5 class="fw-bold mb-2">Popis zápasu</h5>
          <?php the_content(); ?>
        </div>
      <?php endif; ?>

      <!-- ================================================================
           SPRÁVCOVSKÝ FORMULÁŘ — viditelný JEN přihlášeným správcům
           ================================================================ -->
      <?php if (current_user_can('edit_post', get_the_ID())) : ?>
      <!--
        current_user_can('edit_post', ID) = WP funkce pro kontrolu oprávnění.
        Vrací true jen pokud přihlášený uživatel SMÍ upravit tento příspěvek.
        Pro nepřihlášené a čtenáře vrátí false → celý blok se nezobrazí.
        Toto je KLÍČOVÉ pro bezpečnost — nikdy nezobrazovat editaci veřejně!
      -->
        <div class="border rounded p-4 mb-4 admin-box">
          <h5 class="fw-bold mb-3 admin-box__title">
            ✏️ Správa zápasu
            <span class="badge bg-secondary ms-2 admin-box__badge">pouze pro správce</span>
          </h5>

          <?php
          // Flash zpráva po úspěšném uložení (přidána do URL jako ?slavoj_saved=1)
          if (isset($_GET['slavoj_saved']) && $_GET['slavoj_saved'] === '1') :
          ?>
            <div class="alert alert-success py-2 mb-3">✅ Zápas byl úspěšně uložen.</div>
          <?php endif; ?>

          <!--
            FORMULÁŘ pro úpravu skóre a střelců z frontendu (bez nutnosti jít do adminu).

            method="post"  = data se odesílají v těle požadavku (ne v URL)
            action="admin-post.php" = WordPress endpoint pro zpracování formulářů.
              WP přijme data a zavolá akci 'admin_post_{action}'.

            BEZPEČNOST FORMULÁŘE:
          -->
          <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

            <?php wp_nonce_field('slavoj_update_zapas_' . get_the_ID(), 'slavoj_zapas_update_nonce'); ?>
            <!--
              wp_nonce_field() = OCHRANA PROTI CSRF (Cross-Site Request Forgery).

              CSRF útok: útočník vytvoří stránku s formulářem, který odešle data
              na NÁŠ web. Pokud by nebyla ochrana, správce by nevědomky
              změnil skóre zápasu pouhým kliknutím na odkaz.

              Nonce = "Number used ONCE" = jednorázový token.
              WP vygeneruje skrytý <input> s unikátním kódem.
              Při zpracování formuláře WP ověří, že kód odpovídá —
              pokud ne (= formulář nepřišel z naší stránky), odmítne ho.
            -->

            <!-- Skrytá pole: jakou akci provést + ID příspěvku -->
            <input type="hidden" name="action" value="slavoj_update_zapas">
            <input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID()); ?>">

            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label for="skore_inline" class="form-label fw-semibold">Skóre</label>
                <input type="text" id="skore_inline" name="skore"
                       class="form-control"
                       value="<?php echo esc_attr($skore); ?>"
                       placeholder="např. 3:1">
                <div class="form-text">Ponechte prázdné pro nadcházející zápas.</div>
              </div>
              <div class="col-md-8">
                <label for="strelci_inline" class="form-label fw-semibold">Střelci</label>
                <input type="text" id="strelci_inline" name="strelci"
                       class="form-control"
                       value="<?php echo esc_attr($strelci); ?>"
                       placeholder="např. 2× Novák, Bejček">
              </div>
            </div>

            <!-- Rychlý výběr střelce ze soupisky -->
            <?php
            $tym_kat_terms = get_the_terms(get_the_ID(), 'kategorie-tymu');
            if ($tym_kat_terms && !is_wp_error($tym_kat_terms)) :
                $tym_slug_val = $tym_kat_terms[0]->slug;
                $hraci_q = new WP_Query(array(
                    'post_type'      => 'hrac',
                    'posts_per_page' => -1,
                    'orderby'        => 'meta_value_num',
                    'meta_key'       => 'cislo',
                    'order'          => 'ASC',
                    'meta_query'     => array(
                        array(
                            'key'     => 'tym_slug',
                            'value'   => $tym_slug_val,
                            'compare' => '=',
                        ),
                    ),
                ));

                if ($hraci_q->have_posts()) :
            ?>
            <div class="mb-3">
              <label class="form-label fw-semibold">Tabulka střelců – výběr hráče ze soupisky</label>
              <div class="table-responsive">
                <table class="table table-sm table-bordered mb-2">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Jméno hráče</th>
                      <th>Přidat jako střelce</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($hraci_q->have_posts()) : $hraci_q->the_post(); ?>
                      <tr>
                        <td><?php echo esc_html(get_post_meta(get_the_ID(), 'cislo', true) ?: '—'); ?></td>
                        <td><?php the_title(); ?></td>
                        <td>
                          <button type="button"
                                  class="btn btn-sm btn-outline-primary slavoj-add-scorer"
                                  data-name="<?php echo esc_attr(get_the_title()); ?>">
                            + Přidat
                          </button>
                        </td>
                      </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                  </tbody>
                </table>
              </div>
              <div class="form-text">Kliknutím na tlačítko přidáte hráče do pole Střelci.</div>
            </div>
            <?php
                endif;
            endif;
            ?>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                Uložit skóre a střelce
              </button>
              <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="btn btn-outline-secondary">
                Plné úpravy v adminu →
              </a>
            </div>
          </form>
        </div>

        <script>
        /**
         * VANILLA JAVASCRIPT — přidání střelce kliknutím na tlačítko.
         *
         * (function() { ... })(); = IIFE (Immediately Invoked Function Expression)
         *   Funkce se okamžitě spustí a její proměnné NEZANÁŠÍ globální scope.
         *   → nemůže kolidovat s jinými skripty na stránce.
         *
         * querySelectorAll() = najde VŠECHNY elementy s danou CSS třídou
         * forEach()          = projde je jeden po druhém
         * addEventListener() = naváže událost (klik) na funkci
         * this.dataset.name  = přečte data-name atribut z tlačítka (jméno hráče)
         *
         * Logika: klik na "+ Přidat" → jméno hráče se připojí do pole Střelci
         * (oddělené čárkou, pokud tam už něco je)
         */
        (function () {
          document.querySelectorAll('.slavoj-add-scorer').forEach(function (btn) {
            btn.addEventListener('click', function () {
              var name  = this.dataset.name;
              var input = document.getElementById('strelci_inline');
              input.value = input.value ? input.value + ', ' + name : name;
              input.focus();
            });
          });
        })();
        </script>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
