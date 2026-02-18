<?php get_header(); ?>




<div class="container py-5">
    <h2 class="mb-0">Zápasy</h2>
    <p class="text-muted mb-4">Přehled všech zápasů TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <div class="d-flex gap-3 mb-4">
      <select class="form-select " style="width: 20%; background-color: #233D97 ; color: white;">
        <option>Muži A</option>
        <option>Muži B</option>
        <option>Dorost</option>
        <option>Starší žáci</option>
      </select>

      <select class="form-select bg-light" style="width: 20%;">
        <option>Sezóna 2025/26</option>
        <option>Sezóna 2024/25</option>
      </select>

      <select class="form-select " style="width: 20%; background-color: #233D97 ; color: white;">
        <option>Všechny zápasy</option>
        <option>Odehrané zápasy</option>
        <option>Budoucí zápasy</option>
      </select>
    </div>
  </div>

<div class="fluid">
<div class="row">
  
      <div class="col-5">
          <div class="blue-bar-p"></div>
      </div>

    <div class="col-1">
        <img src="..." alt="Slavoj Mýto">
    </div>

      <div class="col-6">
          <div class="blue-bar-l"></div>
      </div>
</div>
</div>

<div class="contanier " >
    <h1 style="text-align: center;">Muži A</h1>
</div>


  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Zápas 1 -->
        <div class="match-card mb-3 p-3 border rounded-4">
          <div class="row">
            <div class="col-md-3 small text-muted">
              08. 08. 2025 v 18:00 <br>
              <span class="text-secondary">Střelci: 2x Schmid, Bejček, Otec</span>
            </div>
            <div class="col-md-9 d-flex justify-content-between align-items-center">
              <strong>TJ Slavoj Mýto</strong>
              <span>4 : 2</span>
              <strong>Rapid Plzeň</strong>
            </div>
          </div>
          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Hosté</span>
            <span>Domácí</span>
          </div>
        </div>

        <!-- Zápas 2 -->
        <div class="match-card mb-3 p-3 border rounded-4">
          <div class="row">
            <div class="col-md-3 small text-muted">
              15. 08. 2025 v 18:00 <br>
              <span class="text-secondary">Střelci:</span>
            </div>
            <div class="col-md-9 d-flex justify-content-between align-items-center">
              <strong>TJ Slavoj Mýto</strong>
              <span>vs</span>
              <strong>TJ Chotěšov</strong>
            </div>
          </div>
          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Domácí</span>
            <span>Hosté</span>
          </div>
        </div>

        <!-- Zápas 3 -->
        <div class="match-card mb-3 p-3 border rounded-4">
          <div class="row">
            <div class="col-md-3 small text-muted">
              08. 08. 2025 v 18:00 <br>
              <span class="text-secondary">Střelci:</span>
            </div>
            <div class="col-md-9 d-flex justify-content-between align-items-center">
              <strong>TJ Slavoj Mýto</strong>
              <span>vs</span>
              <strong>FK Bohemia Kaznějov</strong>
            </div>
          </div>
          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Hosté</span>
            <span>Domácí</span>
          </div>
        </div>

      </div>
    </div>

  </div>


  <?php
        // Získání příspěvků z kategorie "Procvičování"
        $args = array(
            'category_name' => 'zapasy',
            'posts_per_page' => 20
        );
        $zapasy_query = new WP_Query( $args );
        if( $zapasy_query->have_posts() ):
        while( $zapasy_query->have_posts() ): $zapasy_query->the_post();
            ?>
    <div class="match-card mb-3 p-3 border rounded-4">
          <div class="row">
            <div class="col-md-3 small text-muted">
              08. 08. 2025 v 18:00 <br>
              <span class="text-secondary">Střelci: 2x Schmid, Bejček, Otec</span>
            </div>
            <div class="col-md-9 d-flex justify-content-between align-items-center">
              <strong>TJ Slavoj Mýto</strong>
              <span>4 : 2</span>
              <strong>Rapid Plzeň</strong>
            </div>
          </div>
          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Hosté</span>
            <span>Domácí</span>
          </div>
        </div>

    <?php
        endwhile;
        endif;
        wp_reset_postdata();
?>

  <?php get_footer(); ?>