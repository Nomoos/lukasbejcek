<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TJ Slavoj Mýto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>


  <div class="bg-light border-bottom">
    <div class="container d-flex justify-content-between align-items-center py-2">
      <div class="d-flex align-items-center">
        <img src="<?php bloginfo('template_directory'); ?>/img/logo.png" alt="logo" height="50" class="me-2">
        <h1 class="fs-4 m-0">TJ Slavoj Mýto</h1>
      </div>
      <nav>
      <?php

            wp_nav_menu(array(
            'theme_location' => 'main_menu', 
            'container' => 'ul', 
            'menu_class' => 'nav',
            'depth' => 2, 
            ));
            ?>
      </nav>
    </div>
  </div>
