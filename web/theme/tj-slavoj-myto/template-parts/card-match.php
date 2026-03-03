<?php
/**
 * Template part: card-match.php
 * Karta jednoho zápasu (použitelná ve smyčce i mimo ni).
 * Voláno přes get_template_part('template-parts/card', 'match', $args).
 *
 * Očekávané $args:
 *   'datum'      => string  – datum ve formátu Y-m-d (raw, pro datetime atribut)
 *   'datum_fmt'  => string  – datum formátované pro zobrazení (j. n. Y)
 *   'cas'        => string  – čas zápasu (H:i)
 *   'domaci'     => string  – název domácího týmu
 *   'hoste'      => string  – název hostujícího týmu
 *   'skore'      => string  – skóre (napr. "3:1"), prázdné = nadcházející
 *   'strelci'    => string  – jména střelců, prázdné = nezobrazit
 *   'card_cls'   => string  – CSS modifikátory karty (played, upcoming, win, loss, draw)
 *   'score_cls'  => string  – CSS modifikátor score badge
 *   'badge_cls'  => string  – CSS třídy odznaku
 *   'badge_text' => string  – text odznaku
 *   'lbl_levy'   => string  – label levého týmu (Domácí/Hosté)
 *   'lbl_pravy'  => string  – label pravého týmu
 *   'home_cls'   => string  – CSS třídy pro domácí tým
 *   'away_cls'   => string  – CSS třídy pro hostující tým
 */

$datum      = isset($args['datum'])      ? $args['datum']      : '';
$datum_fmt  = isset($args['datum_fmt'])  ? $args['datum_fmt']  : '';
$cas        = isset($args['cas'])        ? $args['cas']        : '';
$domaci     = isset($args['domaci'])     ? $args['domaci']     : '';
$hoste      = isset($args['hoste'])      ? $args['hoste']      : '';
$skore      = isset($args['skore'])      ? $args['skore']      : '';
$strelci    = isset($args['strelci'])    ? $args['strelci']    : '';
$card_cls   = isset($args['card_cls'])   ? $args['card_cls']   : '';
$score_cls  = isset($args['score_cls'])  ? $args['score_cls']  : 'match-card__score';
$badge_cls  = isset($args['badge_cls'])  ? $args['badge_cls']  : 'badge';
$badge_text = isset($args['badge_text']) ? $args['badge_text'] : '';
$lbl_levy   = isset($args['lbl_levy'])   ? $args['lbl_levy']   : 'Domácí';
$lbl_pravy  = isset($args['lbl_pravy'])  ? $args['lbl_pravy']  : 'Hosté';
$home_cls   = isset($args['home_cls'])   ? $args['home_cls']   : 'match-card__team match-card__team--home';
$away_cls   = isset($args['away_cls'])   ? $args['away_cls']   : 'match-card__team match-card__team--away';

$je_odehrany = !empty($skore);
?>
<article class="match-card <?php echo esc_attr($card_cls); ?>"
         aria-label="<?php echo esc_attr(sprintf('%s vs %s', $domaci, $hoste)); ?>">

  <!-- META: datum + čas + odznak stavu -->
  <div class="match-card__meta">
    <time class="match-card__time"
          <?php if ($datum) : ?>datetime="<?php echo esc_attr($datum); ?>"<?php endif; ?>>
      <?php
      echo esc_html($datum_fmt);
      if ($cas) {
          echo ' &bull; ' . esc_html($cas);
      }
      ?>
    </time>
    <span class="match-card__badge <?php echo esc_attr($badge_cls); ?>">
      <?php echo esc_html($badge_text); ?>
    </span>
  </div>

  <!-- MAIN: týmy + skóre (stacked na mobilu, řádek na desktopu) -->
  <div class="match-card__main">
    <div class="match-card__teams">
      <div class="<?php echo esc_attr($home_cls); ?>">
        <?php echo esc_html($domaci); ?>
      </div>
      <div class="<?php echo esc_attr($score_cls); ?>"
           aria-label="Skóre: <?php echo $je_odehrany ? esc_attr($skore) : 'nevyhodnoceno'; ?>">
        <?php echo $je_odehrany ? esc_html($skore) : 'vs'; ?>
      </div>
      <div class="<?php echo esc_attr($away_cls); ?>">
        <?php echo esc_html($hoste); ?>
      </div>
    </div>

    <!-- SUB: popisky stran (Domácí / Hosté) -->
    <div class="match-card__sub">
      <span><?php echo esc_html($lbl_levy); ?></span>
      <span><?php echo esc_html($lbl_pravy); ?></span>
    </div>
  </div>

  <!-- STŘELCI (jen odehrané zápasy se zadanými střelci) -->
  <?php if ($je_odehrany && $strelci) : ?>
  <div class="match-card__scorers">
    &#9917; <?php echo esc_html($strelci); ?>
  </div>
  <?php endif; ?>

</article>
