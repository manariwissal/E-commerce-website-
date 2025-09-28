<?php 
  include_once('./includes/headerNav.php');
?>



<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->

<header>
  <!-- top head action, search etc in php -->
  <?php require_once './includes/topheadactions.php'; ?>

  <!-- desktop navigation -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
 <?php require_once './includes/mobilenav.php'; ?>
</header>

<!--
    - MAIN
  -->

<main>

  <div class="product-container">
    <div class="container">
      <!--
          - SIDEBAR
        -->
      <!-- CATEGORY SIDE BAR MOBILE MENU -->
      <?php require_once './includes/categorysidebar.php' ?>
      <!-- ############################# -->

      <div class="product-box">
        <!-- get id and url for each category and display its dat from table her in this secton -->
        <div class="product-main">

          <!--  -->
          <!-- about us section -->
          <div class="about-us">
            <div class="about-us-section">
              <!-- about us text -->
              <div class="about-us-content">
                <div class="about-us-text">
                  <h1 id="about-title" style="text-align: center;">À propos de nous</h1>
                  <br>
                  <h2 style="text-align: center;">
                  Bienvenue sur <span id="about-title">AYOUBSHOP</span>
                  </h2>
                  <p>
                    <strong id="about-title">AYOUBSHOP</strong> est une plateforme e-commerce professionnelle. Ici, nous vous proposons uniquement du contenu intéressant, que vous allez beaucoup apprécier.
                  </p>
                  <p>Nous sommes déterminés à vous offrir le meilleur du e-commerce, avec un accent particulier sur la fiabilité et la vente en ligne.</p>
                  <p>Nous travaillons avec passion pour faire d'AYOUBSHOP un site incontournable du commerce en ligne. Nous espérons que vous apprécierez notre boutique autant que nous aimons la faire vivre pour vous.</p>
                  <p style="margin-top: 30px; font-weight: bold; color: #a1001a;">Merci de votre visite sur <strong id="about-title">AYOUBSHOP</strong><br>Excellente journée à vous !</p>
                </div>
              </div>
            </div>
          </div>
          <!--  -->


        </div>
      </div>
    </div>
  </div>

  <!--
      - TESTIMONIALS, CTA & SERVICE
    -->

  <!--
      - BLOG
    -->

</main>

<?php require_once './includes/footer.php'; ?>