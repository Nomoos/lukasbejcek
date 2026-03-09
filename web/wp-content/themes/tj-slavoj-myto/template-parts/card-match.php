<?php
/**
 * Template part: card-match.php
 * Karta jednoho zápasu – fotbalový styl.
 * Voláno přes get_template_part('template-parts/card', 'match', $args).
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

// Skóre pro mobilní inline zobrazení
$score_home_cls = 'match-card__score-inline';
$score_away_cls = 'match-card__score-inline';
if ($je_odehrany && strpos($score_cls, '--win') !== false) {
    $score_home_cls .= ' match-card__score-inline--home-win';
    $score_away_cls .= ' match-card__score-inline--home-loss';
} elseif ($je_odehrany && strpos($score_cls, '--loss') !== false) {
    $score_home_cls .= ' match-card__score-inline--home-loss';
    $score_away_cls .= ' match-card__score-inline--home-win';
} elseif ($je_odehrany) {
    $score_home_cls .= ' match-card__score-inline--draw';
    $score_away_cls .= ' match-card__score-inline--draw';
} else {
    $score_home_cls .= ' match-card__score-inline--upcoming';
    $score_away_cls .= ' match-card__score-inline--upcoming';
}

// Rozbij skóre na dvě čísla pro mobilní zobrazení
$skore_domaci = '';
$skore_hoste  = '';
if ($je_odehrany && strpos($skore, ':') !== false) {
    $parts = explode(':', $skore);
    $skore_domaci = trim($parts[0]);
    $skore_hoste  = trim($parts[1]);
}
?>
<article class="match-card <?php echo esc_attr($card_cls); ?>"
         aria-label="<?php echo esc_attr(sprintf('%s vs %s', $domaci, $hoste)); ?>">

  <!-- BAREVNÝ PROUŽEK NAHOŘE -->
  <div class="match-card__strip" aria-hidden="true"></div>

  <div class="match-card__body">

    <!-- META: datum + odznak -->
    <div class="match-card__meta">
      <time class="match-card__time"
            <?php if ($datum) : ?>datetime="<?php echo esc_attr($datum); ?>"<?php endif; ?>>
        <?php
        echo esc_html($datum_fmt);
        if ($cas) echo ' &bull; ' . esc_html($cas);
        ?>
      </time>
      <span class="match-card__badge <?php echo esc_attr($badge_cls); ?>">
        <?php echo esc_html($badge_text); ?>
      </span>
    </div>

    <!-- MOBILNÍ LAYOUT: každý tým na řádku se svým skóre -->
    <div class="match-card__teams">

      <!-- Domácí – mobilní řádek -->
      <div class="match-card__team-row">
        <div class="<?php echo esc_attr($home_cls); ?>"><?php echo esc_html($domaci); ?></div>
        <?php if ($je_odehrany) : ?>
          <div class="<?php echo esc_attr($score_home_cls); ?>"><?php echo esc_html($skore_domaci); ?></div>
        <?php endif; ?>
      </div>

      <div class="match-card__divider"></div>

      <!-- Hosté – mobilní řádek -->
      <div class="match-card__team-row">
        <div class="<?php echo esc_attr($away_cls); ?>"><?php echo esc_html($hoste); ?></div>
        <?php if ($je_odehrany) : ?>
          <div class="<?php echo esc_attr($score_away_cls); ?>"><?php echo esc_html($skore_hoste); ?></div>
        <?php endif; ?>
      </div>

      <!-- DESKTOP LAYOUT: tým | skóre | tým (zobrazeno přes CSS na ≥640px) -->
      <div class="<?php echo esc_attr($home_cls); ?> d-none d-sm-block">
        <?php echo esc_html($domaci); ?>
      </div>

      <div class="<?php echo esc_attr($score_cls); ?> d-none d-sm-block"
           aria-label="Skóre: <?php echo $je_odehrany ? esc_attr($skore) : 'nevyhodnoceno'; ?>">
        <?php echo $je_odehrany ? esc_html($skore) : 'vs'; ?>
      </div>

      <div class="<?php echo esc_attr($away_cls); ?> d-none d-sm-block">
        <?php echo esc_html($hoste); ?>
      </div>

    </div>

    <!-- Popisky Domácí / Hosté (desktop) -->
    <div class="match-card__sub">
      <span><?php echo esc_html($lbl_levy); ?></span>
      <span><?php echo esc_html($lbl_pravy); ?></span>
    </div>

    <!-- STŘELCI -->
    <?php if ($je_odehrany && $strelci) : ?>
      <div class="match-card__scorers">
        &#9917; <?php echo esc_html($strelci); ?>
      </div>
    <?php endif; ?>

  </div><!-- /.match-card__body -->

</article>
