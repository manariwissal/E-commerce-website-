<?php
session_start();
// Initialisation du panier s'il n'existe pas
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './includes/functions.php';
include_once('./includes/headerNav.php');

// Get all banner products
$banner_products = get_banner_details();
// Get all category bar products
$catgeory_bar_products = get_category_bar_products();

// Get categories
$categories = get_categories();
$clothes = get_clothes_category();
$footwears = get_footwear_category();
$jewelries = get_jewelry_category();
$perfumes = get_perfume_category();
$cosmetics = get_cosmetics_category();
$glasses = get_glasses_category();
$bags = get_bags_category();

// Get all new arrivals
$new_arrivals1 = get_new_arrivals();
$new_arrivals2 = get_new_arrivals();

// Get trending products
$trending_products1 = get_trending_products();
$trending_products2 = get_trending_products();

// Get top rated products
$top_rated_products1 = get_top_rated_products();
$top_rated_products2 = get_top_rated_products();

// Log des IDs des produits
$sql = "SELECT product_id, product_title FROM product LIMIT 5";
$result = mysqli_query($conn, $sql);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
error_log("Produits disponibles : " . print_r($products, true));

// Fonction pour afficher un produit
function displayProduct($row) {
    $product_id = intval($row['product_id']);
    error_log("Affichage du produit ID: " . $product_id);
    ?>
    <div class="showcase">
        <a href="viewdetail.php?id=<?php echo $product_id; ?>" class="showcase-img-box">
            <img src="./admin/upload/<?php echo htmlspecialchars($row['product_img']); ?>" 
                 alt="<?php echo htmlspecialchars($row['product_title']); ?>" 
                 width="70" 
                 class="showcase-img" />
        </a>

        <div class="showcase-content">
            <a href="viewdetail.php?id=<?php echo $product_id; ?>">
                <h4 class="showcase-title">
                    <?php echo htmlspecialchars($row['product_title']); ?>
                </h4>
            </a>

            <a href="viewdetail.php?id=<?php echo $product_id; ?>" class="showcase-category">
                <?php echo "New Arrival!"; ?>
            </a>

            <div class="price-box">
                <p class="price">$<?php echo htmlspecialchars($row['discounted_price']); ?></p>
                <del>$<?php echo htmlspecialchars($row['product_price']); ?></del>
            </div>

            <!-- Ajout du formulaire d'ajout au panier -->
            <form method="POST" action="cart.php" style="margin-top: 10px; display: flex; gap: 8px; align-items: center;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 4px; border-radius: 4px; border: 1px solid #ddd;">
                <button type="submit" name="add_to_cart" style="background-color: #CE5959; color: white; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 14px;">Ajouter au panier</button>
                <button type="button" class="favorite-btn" data-product-id="<?php echo $product_id; ?>" style="background: none; border: none; cursor: pointer; font-size: 22px; color: #CE5959; display: flex; align-items: center;">
                    <i class="far fa-heart"></i>
                </button>
            </form>
            <!-- Fin du formulaire -->
        </div>
    </div>
    <?php
}
?>

<style>
.favorite-btn.favorited i {
    color: #a1001a !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.favorite-btn').forEach(function(btn) {
        const productId = btn.getAttribute('data-product-id');
        updateHeartIcon(btn, productId);
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Effet visuel immédiat
            if (btn.classList.contains('favorited')) {
                btn.classList.remove('favorited');
                btn.querySelector('i').classList.remove('fas');
                btn.querySelector('i').classList.add('far');
            } else {
                btn.classList.add('favorited');
                btn.querySelector('i').classList.remove('far');
                btn.querySelector('i').classList.add('fas');
            }
            toggleFavorite(btn, productId);
        });
    });
});
function updateHeartIcon(btn, productId) {
    const formData = new FormData();
    formData.append('action', 'check');
    formData.append('product_id', productId);
    fetch('includes/favorites.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.is_favorite) {
            btn.classList.add('favorited');
            btn.querySelector('i').classList.remove('far');
            btn.querySelector('i').classList.add('fas');
        } else {
            btn.classList.remove('favorited');
            btn.querySelector('i').classList.remove('fas');
            btn.querySelector('i').classList.add('far');
        }
    });
}
function toggleFavorite(btn, productId) {
    const formData = new FormData();
    formData.append('action', 'toggle');
    formData.append('product_id', productId);
    fetch('includes/favorites.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        // Optionnel : resynchroniser l'état avec la base si besoin
        updateHeartIcon(btn, productId);
    });
}
</script>

<div class="overlay" data-modal-overlay></div>

<!--
    - MODAL
  -->

<!--
    - NOTIFICATION TOAST
  -->

<div class="notification-toast" data-modal>
  <button class="toast-close-btn" data-modal-close>
    <ion-icon name="close-outline"></ion-icon>
  </button>

  <div class="toast-banner">
    <img src="./admin/upload/watch-2.jpg" alt="Smart Watch" width="80" height="70" />
  </div>

  <div class="toast-detail">
    <p class="toast-message">Someone in new just bought</p>

    <p class="toast-title">Smart Watch</p>

    <p class="toast-meta"><time datetime="PT2M">2 Minutes</time> ago</p>
  </div>
</div>


<!--
    - HEADER
  -->

<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- desktop navigation -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>

  <div class="nav-icons">
    <a href="view-favorites.php"><i class="ion-heart"></i></a>
    <a href="cart.php" style="position: relative;">
        <i class="ion-bag"></i>
    </a>
    <a href="profile.php"><i class="ion-person"></i></a>
  </div>
</header>

<!--
    - MAIN
  -->

<main>
  <!--
      - BANNER: Coursal
    -->

  <div class="banner">
    <div class="container">
      <div class="slider-container has-scrollbar">
        <!-- Display data from db in banner -->
        <?php
        while ($row = mysqli_fetch_assoc($banner_products)) {
        ?>

          <div class="slider-item">
            <img src="images/carousel/<?php
                                      echo $row['banner_image'];
                                      ?>" alt="women's latest fashion sale" class="banner-img" />

            <div class="banner-content">
              <p class="banner-subtitle">
                <?php
                echo $row['banner_subtitle'];
                ?>
              </p>

              <h2 class="banner-title">
                <?php
                echo $row['banner_title'];
                ?>
              </h2>

              <p class="banner-text">starting at &dollar;
                <b><?php echo $row['banner_items_price']; ?></b>.00
              </p>

              <a href="#" class="banner-btn">Shop now</a>
            </div>
          </div>

        <?php
        }
        ?>
        <!--  -->
      </div>
    </div>
  </div>

  <!--
      - PRODUCT
    -->

  <div class="product-container">
    <div class="container">
      <!--
          - SIDEBAR
        -->
      <!-- adding side bar php page -->
      <?php require_once './includes/categorysidebar.php' ?>


      <div class="product-box">
        <!--
            - PRODUCT MINIMAL
          -->

        <div class="product-minimal">
          <div class="product-showcase">
            <h2 class="title">New Arrivals</h2>

            <div class="showcase-wrapper has-scrollbar">
              <!-- new arrival container 1 -->

              <div class="showcase-container">
                <!-- get element from table with id less than 4 -->
                <?php
                $itemID;
                $counter = 0;
                while ($row1 = mysqli_fetch_assoc($new_arrivals1)) {
                  // prints 4 items and then break out
                  if ($counter == 4) {
                    break;
                  }

                ?>
                  <?php displayProduct($row1); ?>
                <?php
                  $itemID = $row1['product_id'];
                  $counter += 1;
                }
                ?>


              </div>
              <!--  -->
              <!-- new arrival container 2 -->
              <div class="showcase-container">
                <!-- get element from table with id greaer than 4 -->
                <?php
                // $itemID = 4;
                $counter = 0;
                while ($row2 = mysqli_fetch_assoc($new_arrivals2)) {
                  // breaks after printing 4 items
                  if ($counter == 4) {
                    break;
                  }
                  if ($row2['product_id'] > $itemID) {

                ?>
                    <?php displayProduct($row2); ?>
                <?php
                    $counter += 1;
                  }
                }
                ?>

                <!--  -->
              </div>
            </div>
          </div>

          <!-- Trending Items -->
          <div class="product-showcase">
            <h2 class="title">Trending</h2>

            <div class="showcase-wrapper has-scrollbar">
              <!-- get data from trending table in db -->
              <!-- trending container 1 -->
              <div class="showcase-container">
                <!-- get element from table with id less than 4 -->
                <?php
                $itemID;
                $counter = 0;
                while ($row1 = mysqli_fetch_assoc($trending_products1)) {
                  // prints 4 items and then break out
                  if ($counter == 4) {
                    break;
                  }

                ?>
                  <?php displayProduct($row1); ?>
                <?php
                  $itemID = $row1['product_id'];
                  $counter += 1;
                }
                ?>


              </div>
              <!-- trending container 2 -->
              <div class="showcase-container">
                <!-- get element from table with id greaer than 4 -->
                <?php
                // $itemID = 4;
                $counter = 0;
                while ($row2 = mysqli_fetch_assoc($trending_products2)) {
                  // breaks after printing 4 items
                  if ($counter == 4) {
                    break;
                  }
                  if ($row2['product_id'] > $itemID) {

                ?>
                    <?php displayProduct($row2); ?>
                <?php
                    $counter += 1;
                  }
                }
                ?>

                <!--  -->
              </div>
              <!--  -->
            </div>
          </div>

          <div class="product-showcase">
            <h2 class="title">Top Rated</h2>
            <!-- Load data from top rated table -->
            <div class="showcase-wrapper has-scrollbar">
              <!-- top rated container 1 -->
              <div class="showcase-container">
                <!-- get element from table with id less than 4 -->
                <?php
                $itemID;
                $counter = 0;
                while ($row1 = mysqli_fetch_assoc($top_rated_products1)) {
                  // prints 4 items and then break out
                  if ($counter == 4) {
                    break;
                  }

                ?>
                  <?php displayProduct($row1); ?>
                <?php
                  $itemID = $row1['product_id'];
                  $counter += 1;
                }
                ?>


              </div>
              <!-- top rated conatiner 2 -->
              <div class="showcase-container">
                <!-- get element from table with id greaer than 4 -->
                <?php
                // $itemID = 4;
                $counter = 0;
                while ($row2 = mysqli_fetch_assoc($top_rated_products2)) {
                  // breaks after printing 4 items
                  if ($counter == 4) {
                    break;
                  }
                  if ($row2['product_id'] > $itemID) {

                ?>
                    <?php displayProduct($row2); ?>
                <?php
                    $counter += 1;
                  }
                }
                ?>

                <!--  -->
              </div>
              <!--  -->
            </div>
          </div>
        </div>

        <!--
            - PRODUCT FEATURED
          -->

<?php require_once './includes/dealoftheday.php' ?>


        <!--
            - PRODUCT GRID
          -->

        <div class="product-main">
          <h2 class="title">Nos Produits</h2>
          <div class="product-grid">
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY product_id DESC LIMIT 24");
            if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()): ?>
              <div class="showcase">
                <div class="showcase-banner">
                  <img src="./admin/upload/<?php echo $row['product_img']; ?>" alt="<?php echo htmlspecialchars($row['product_title']); ?>" width="300" class="product-img default" />
                </div>
                <div class="showcase-content">
                  <a href="viewdetail.php?id=<?php echo intval($row['product_id']) ?>" class="showcase-category">
                    <?php echo $row['product_title'] ?>
                  </a>
                  <a href="viewdetail.php?id=<?php echo intval($row['product_id']) ?>">
                    <h3 class="showcase-title"><?php echo $row['product_desc'] ?></h3>
                  </a>
                  <div class="showcase-rating">
                    <ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon><ion-icon name="star"></ion-icon>
                  </div>
                  <div class="price-box">
                    <p class="price">$<?php echo $row['discounted_price'] ?></p>
                    <del>$<?php echo $row['product_price'] ?></del>
                  </div>
                  <form method="POST" action="cart.php" style="margin-top: 10px; display: flex; gap: 8px; align-items: center;">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['product_title']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $row['discounted_price']; ?>">
                    <input type="hidden" name="product_img" value="<?php echo htmlspecialchars($row['product_img']); ?>">
                    <input type="number" name="product_qty" value="1" min="1" style="width: 60px; padding: 4px; border-radius: 4px; border: 1px solid #ddd;">
                    <button type="submit" name="add_to_cart" style="background-color: #CE5959; color: white; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 14px;">Ajouter au panier</button>
                    <button type="button" class="favorite-btn" data-product-id="<?php echo $row['product_id']; ?>" style="background: none; border: none; cursor: pointer; font-size: 22px; color: #CE5959; display: flex; align-items: center;">
                      <i class="far fa-heart"></i>
                    </button>
                  </form>
                </div>
              </div>
            <?php endwhile; else: ?>
              <p>Aucun produit trouvé.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--
      - TESTIMONIALS, CTA & SERVICE
    -->

  <div>
    <div class="container">
      <div class="testimonials-box">
        <!--
            - TESTIMONIALS
          -->

        <div class="testimonial">
          <h2 class="title">testimonial</h2>

          <div class="testimonial-card">
            <img src="./images/testimonial-1_copy.jpg" alt="alan doe" class="testimonial-banner" width="80" height="80" />

            <p class="testimonial-name">IQSF</p>

            <p class="testimonial-title">CEO & Founder Invision</p>

            <img src="./images/icons/quotes.svg" alt="quotation" class="quotation-img" width="26" />

            <p class="testimonial-desc">
            You guys are the best! Keep up the great work!
            </p>
          </div>
        </div>

        <!--
            - CTA
          -->

        <div class="cta-container">
          <!-- -->
          <img src="./images/cta-banner-sale.jpg" alt="summer collection" class="cta-banner" />

          <a href="#" class="cta-content">
            <p class="discount">25% Discount</p>

            <h2 class="cta-title">Collection</h2>

            <p class="cta-text">Starting $20</p>

            <button class="cta-btn">Shop now</button>
          </a>
        </div>

        <!--
            - SERVICE
          -->

        <div class="service">
          <h2 class="title">Our Services</h2>

          <div class="service-container">
            <a href="#" class="service-item">
              <div class="service-icon">
                <ion-icon name="boat-outline"></ion-icon>
              </div>

              <div class="service-content">
                <h3 class="service-title">Worldwide Delivery</h3>
                <p class="service-desc">For Order Over $100</p>
              </div>
            </a>

            <a href="#" class="service-item">
              <div class="service-icon">
                <ion-icon name="rocket-outline"></ion-icon>
              </div>

              <div class="service-content">
                <h3 class="service-title">Next Day delivery</h3>
                <p class="service-desc">UK Orders Only</p>
              </div>
            </a>

            <a href="#" class="service-item">
              <div class="service-icon">
                <ion-icon name="call-outline"></ion-icon>
              </div>

              <div class="service-content">
                <h3 class="service-title">Best Online Support</h3>
                <p class="service-desc">Hours: 8AM - 11PM</p>
              </div>
            </a>

            <a href="#" class="service-item">
              <div class="service-icon">
                <ion-icon name="arrow-undo-outline"></ion-icon>
              </div>

              <div class="service-content">
                <h3 class="service-title">Return Policy</h3>
                <p class="service-desc">Easy & Free Return</p>
              </div>
            </a>

            <a href="#" class="service-item">
              <div class="service-icon">
                <ion-icon name="ticket-outline"></ion-icon>
              </div>

              <div class="service-content">
                <h3 class="service-title">30% money back</h3>
                <p class="service-desc">For Order Over $100</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--
      - BLOG
    -->
    <!-- image path: ./images/blog/ -->

  <div class="blog">
    <div class="container">
      <div class="blog-container has-scrollbar">
        <div class="blog-card">
          <a href="#">
            <img src="./images/blog/blog-1.jpg" alt="Clothes Retail KPIs 2021 Guide for Clothes Executives" width="300" class="blog-banner" />
          </a>

          <div class="blog-content">
            <a href="#" class="blog-category">Fashion</a>

            <a href="#">
              <h3 class="blog-title">
                Clothes Retail KPIs 2021 Guide for Clothes Executives.
              </h3>
            </a>

            <p class="blog-meta">
              By <cite>Mr Admin</cite> /
              <time datetime="2022-04-06">Apr 06, 2022</time>
            </p>
          </div>
        </div>

        <div class="blog-card">
          <a href="#">
            <img src="./images/blog/blog-2.jpg" alt="Curbside fashion Trends: How to Win the Pickup Battle." class="blog-banner" width="300" />
          </a>

          <div class="blog-content">
            <a href="#" class="blog-category">Clothes</a>

            <h3>
              <a href="#" class="blog-title">Curbside fashion Trends: How to Win the Pickup Battle.</a>
            </h3>

            <p class="blog-meta">
              By <cite>Mr Robin</cite> /
              <time datetime="2022-01-18">Jan 18, 2022</time>
            </p>
          </div>
        </div>

        <div class="blog-card">
          <a href="#">
            <img src="./images/blog/blog-3.jpg" alt="EBT vendors: Claim Your Share of SNAP Online Revenue." class="blog-banner" width="300" />
          </a>

          <div class="blog-content">
            <a href="#" class="blog-category">Shoes</a>

            <h3>
              <a href="#" class="blog-title">EBT vendors: Claim Your Share of SNAP Online Revenue.</a>
            </h3>

            <p class="blog-meta">
              By <cite>Mr Selsa</cite> /
              <time datetime="2022-02-10">Feb 10, 2022</time>
            </p>
          </div>
        </div>

        <div class="blog-card">
          <a href="#">
            <img src="./images/blog/blog-4.jpg" alt="Curbside fashion Trends: How to Win the Pickup Battle." class="blog-banner" width="300" />
          </a>

          <div class="blog-content">
            <a href="#" class="blog-category">Electronics</a>

            <h3>
              <a href="#" class="blog-title">Curbside fashion Trends: How to Win the Pickup Battle.</a>
            </h3>

            <p class="blog-meta">
              By <cite>Mr Pawar</cite> /
              <time datetime="2022-03-15">Mar 15, 2022</time>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

<?php if (isset($_GET['order']) && $_GET['order'] === 'success') { ?>
  <div class="alert alert-success text-center" style="font-size:1.2rem; font-weight:600;">Commande envoyée avec succès !</div>
<?php } ?>