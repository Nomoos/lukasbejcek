<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?> TJ Slavoj Mýto</title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <!-- HORNÍ NAVBAR -->
  <div class="bg-light border-bottom">
    <div class="container d-flex justify-content-between align-items-center py-2">
      <div class="d-flex align-items-center">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/logo.png" alt="TJ Slavoj Mýto" height="50" class="me-2">
        <h1 class="fs-4 m-0">TJ Slavoj Mýto</h1>
      </div>
      <nav>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'main_menu',
            'container'      => 'ul',
            'menu_class'     => 'nav',
            'depth'          => 2,
            'fallback_cb'    => 'slavoj_fallback_menu',
        ));
        ?>
      </nav>
    </div>
  </div>

<?php
/**
 * Záložní menu pokud není nastaveno v administraci
 */
function slavoj_fallback_menu() {
    echo '<ul class="nav">';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">Domů</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/zapasy/')) . '">Zápasy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/tymy/')) . '">Týmy</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/galerie/')) . '">Galerie</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/historie/')) . '">Historie</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/sponzori/')) . '">Sponzoři</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/kontakty/')) . '">Kontakty</a></li>';
    echo '</ul>';
}
?>
