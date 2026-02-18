<?php get_header(); ?>
 
 
 <!-- OBSAH -->
  <div class="container py-5">
    <h2 class="mb-0">Týmy</h2>
    <p class="text-muted mb-4">Přehled všech týmů TJ Slavoj Mýto</p>

    <!-- FILTRY -->
    <div class="d-flex gap-3 mb-4">
      <select class="form-select bg-light" style="width: 11%;">
        <option>Muži A</option>
        <option>Muži B</option>
        <option>Dorost</option>
        <option>Starší žáci</option>
      </select>

      <select class="form-select bg-light" style="width: 15%;">
        <option>Sezóna 2025/26</option>
        <option>Sezóna 2024/25</option>
      </select>
    </div>

    <div class="row ">
      <div class="col-md-8">
        <h4 class="fw-bold">Muži A</h4>
        <p class="text-muted">Sezóna 2024/25</p>

        <!-- INFO BOX -->
        <div class="p-3 border rounded-3 mb-4">
          <div class="row">
            <div class="col-md-3"><strong>Počet hráčů:</strong><br>16</div>
            <div class="col-md-3"><strong>Hlavní trenér:</strong><br>Nyklas Petr</div>
            <div class="col-md-3"><strong>Asistent trenéra:</strong><br>Honzík Ivan</div>
            <div class="col-md-3"><strong>Zdravotník:</strong><br>Hrabák Jan</div>
          </div>
        </div>
        
      <div class="col-md-4 text-center">
        <img src="img/logo.png" alt="TJ Slavoj Mýto" class="img-fluid mt-4" style="max-width: 20    200px;">
      </div>
    </div>
    </div>

    <div class="row ">
        <div class="col-md-12">
        
        <h5 class="fw-bold mb-3">Soupiska hráčů</h5>

        <div class="p-3 border rounded-3">
        <div class="row">
           <div class="col-md-12">

             <h5 class="fw-bold mb-3">Soupiska hráčů</h5>

             <div class="p-3 border rounded-3">

               <p class="fw-semibold mb-2">Brankáři</p>
               <div class="row mb-3">
                 <?php slavoj_vypis_soupisku('brankari'); ?>
               </div>

               <p class="fw-semibold mb-2">Hráči</p>
               <div class="row gy-2">
                 <?php slavoj_vypis_soupisku('hraci'); ?>
               </div>

             </div>

           </div>
      </div>

    </div>

      
      
    </div>
  

    <?php get_footer(); ?>