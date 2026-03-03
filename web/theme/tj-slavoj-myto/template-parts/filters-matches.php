<?php
/**
 * Template part: filters-matches.php
 * Filtrovací formulář pro stránku zápasů.
 * Voláno z page-zapasy.php přes get_template_part('template-parts/filters', 'matches', $args).
 *
 * Očekávané $args:
 *   'dostupne_tymy'   => array  – termy taxonomie kategorie-tymu
 *   'dostupne_sezony' => array  – termy taxonomie sezona
 *   'filtr_tym'       => string – aktuálně vybraný tým (slug)
 *   'filtr_sezona'    => string – aktuálně vybraná sezóna (slug)
 *   'filtr_stav'      => string – aktuálně vybraný stav ('vse'|'odehrane'|'neodehrane')
 */

$dostupne_tymy   = isset($args['dostupne_tymy'])   ? $args['dostupne_tymy']   : array();
$dostupne_sezony = isset($args['dostupne_sezony']) ? $args['dostupne_sezony'] : array();
$filtr_tym       = isset($args['filtr_tym'])       ? $args['filtr_tym']       : '';
$filtr_sezona    = isset($args['filtr_sezona'])     ? $args['filtr_sezona']    : '';
$filtr_stav      = isset($args['filtr_stav'])       ? $args['filtr_stav']      : 'vse';
?>
<form method="get" class="filters" role="search" aria-label="Filtrování zápasů">

  <label class="sr-only" for="f-tym">Tým</label>
  <select id="f-tym" name="tym" class="filter filter--primary" onchange="this.form.submit()">
    <option value="">Všechny týmy</option>
    <?php if (!is_wp_error($dostupne_tymy) && !empty($dostupne_tymy)) : foreach ($dostupne_tymy as $t) : ?>
      <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($filtr_tym, $t->slug); ?>>
        <?php echo esc_html($t->name); ?>
      </option>
    <?php endforeach; endif; ?>
  </select>

  <label class="sr-only" for="f-sezona">Sezóna</label>
  <select id="f-sezona" name="sezona" class="filter filter--muted" onchange="this.form.submit()">
    <option value="">Všechny sezóny</option>
    <?php if (!is_wp_error($dostupne_sezony) && !empty($dostupne_sezony)) : foreach ($dostupne_sezony as $s) : ?>
      <option value="<?php echo esc_attr($s->slug); ?>" <?php selected($filtr_sezona, $s->slug); ?>>
        Sezóna <?php echo esc_html($s->name); ?>
      </option>
    <?php endforeach; endif; ?>
  </select>

  <label class="sr-only" for="f-stav">Stav zápasů</label>
  <select id="f-stav" name="stav" class="filter filter--primary" onchange="this.form.submit()">
    <option value="vse"        <?php selected($filtr_stav, 'vse'); ?>>Všechny zápasy</option>
    <option value="odehrane"   <?php selected($filtr_stav, 'odehrane'); ?>>Odehrané</option>
    <option value="neodehrane" <?php selected($filtr_stav, 'neodehrane'); ?>>Nadcházející</option>
  </select>

</form>
