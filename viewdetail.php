<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Démarrer la session
session_start();
include_once('./includes/headerNav.php');
include_once('./includes/config.php');
require_once('./includes/functions.php');
include_once('./includes/favorites.php');

// Récupérer l'ID du produit depuis l'URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Log pour le débogage
error_log("URL complète : " . $_SERVER['REQUEST_URI']);
error_log("Paramètres GET : " . print_r($_GET, true));
error_log("ID du produit : " . $product_id);

// Debug: Get and log all available products
$sql = "SELECT product_id, product_title FROM product";
$result = mysqli_query($conn, $sql);
$available_products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $available_products[] = $row;
}
error_log("Available products: " . print_r($available_products, true));

// Display available products for debugging
echo "<div style='background: #f8f9fa; padding: 20px; margin: 20px; border-radius: 5px;'>";
echo "<h3>Produits disponibles :</h3>";
echo "<ul>";
foreach ($available_products as $product) {
    echo "<li>ID: " . $product['product_id'] . " - " . $product['product_title'] . "</li>";
}
echo "</ul>";
echo "</div>";

// Vérifier si l'ID est valide
if ($product_id <= 0) {
    error_log("ID de produit invalide : " . $product_id);
    die("ID de produit invalide");
}

// Récupérer les détails du produit
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
if (!$stmt) {
    error_log("Erreur de préparation de la requête : " . $conn->error);
    die("Erreur lors de la préparation de la requête");
}

$stmt->bind_param("i", $product_id);
if (!$stmt->execute()) {
    error_log("Erreur d'exécution de la requête : " . $stmt->error);
    die("Erreur lors de l'exécution de la requête");
}

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    error_log("Aucun produit trouvé avec l'ID : " . $product_id);
    die("Produit non trouvé");
}

$row = $result->fetch_assoc();
error_log("Détails du produit : " . print_r($row, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['product_title']); ?> - AYOUBSHOP</title>
    <link rel="stylesheet" type="text/css" href="./css/style-prefix.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <?php require_once './includes/topheadactions.php'; ?>

    <div class="product-detail-container">
        <div class="product-image">
            <img src="./admin/upload/<?php echo htmlspecialchars($row['product_image']); ?>" 
                 alt="<?php echo htmlspecialchars($row['product_title']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($row['product_title']); ?></h1>
            <div class="price">
                <?php 
                $discounted_price = calculateDiscountedPrice(
                    $row['product_price'], 
                    $row['product_discount']
                );
                ?>
                <span class="original-price">$<?php echo number_format($row['product_price'], 2); ?></span>
                <span class="discounted-price">$<?php echo number_format($discounted_price, 2); ?></span>
                <?php if ($row['product_discount'] > 0): ?>
                    <span class="discount-badge">-<?php echo $row['product_discount']; ?>%</span>
                <?php endif; ?>
            </div>
            <div class="description">
                <?php echo nl2br(htmlspecialchars($row['product_desc'])); ?>
            </div>
            <div class="buy-and-cart-btn">
                <a href="cart.php" class="btn btn-primary buy-now-btn">Buy Now</a>
                <button class="btn btn-primary add-to-cart-btn" onclick="addToCart(<?php echo intval($row['product_id']); ?>)">Add to Cart</button>
                <button class="btn btn-primary favorite-btn" data-product-id="<?php echo intval($row['product_id']); ?>">
                    <i class="fas fa-heart"></i> Add to Favorites
                </button>
            </div>
        </div>
    </div>

    <?php require_once './includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get product ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = parseInt(urlParams.get('id'));
        
        console.log('Product ID from URL:', productId);
        
        if (!productId || isNaN(productId) || productId <= 0) {
            console.error('Invalid product ID:', productId);
            return;
        }
        
        const favoriteBtn = document.querySelector('.favorite-btn');
        if (!favoriteBtn) {
            console.error('Favorite button not found');
            return;
        }
        
        // Check initial favorite status
        checkFavoriteStatus(productId);
        
        // Add click event listener
        favoriteBtn.addEventListener('click', function() {
            console.log('Favorite button clicked for product ID:', productId);
            toggleFavorite(productId);
        });
    });

    function checkFavoriteStatus(productId) {
        console.log('Checking favorite status for product ID:', productId);
        
        const formData = new FormData();
        formData.append('action', 'check');
        formData.append('product_id', productId);
        
        fetch('includes/favorites.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Favorite status response:', data);
            const favoriteBtn = document.querySelector('.favorite-btn');
            if (favoriteBtn) {
                if (data.is_favorite) {
                    favoriteBtn.classList.add('active');
                    favoriteBtn.innerHTML = '<i class="fas fa-heart"></i> Retirer des favoris';
                } else {
                    favoriteBtn.classList.remove('active');
                    favoriteBtn.innerHTML = '<i class="far fa-heart"></i> Ajouter aux favoris';
                }
            }
        })
        .catch(error => {
            console.error('Error checking favorite status:', error);
        });
    }

    function toggleFavorite(productId) {
        console.log('Toggling favorite for product ID:', productId);
        
        const formData = new FormData();
        formData.append('action', 'toggle');
        formData.append('product_id', productId);
        
        fetch('includes/favorites.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Toggle favorite response:', data);
            if (data.success) {
                checkFavoriteStatus(productId);
            } else {
                console.error('Error toggling favorite:', data.message);
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error toggling favorite:', error);
            alert('Une erreur est survenue lors de la modification des favoris');
        });
    }
    </script>

    <style>
    .product-detail-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 20px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }
    .product-image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
    .product-info {
        padding: 20px;
    }
    .product-info h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .price {
        font-size: 20px;
        margin-bottom: 20px;
    }
    .original-price {
        text-decoration: line-through;
        color: #999;
        margin-right: 10px;
    }
    .discounted-price {
        color: #CE5959;
        font-weight: bold;
    }
    .discount-badge {
        background-color: #CE5959;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        margin-left: 10px;
    }
    .description {
        margin-bottom: 30px;
        line-height: 1.6;
    }
    .buy-and-cart-btn {
        display: flex;
        gap: 10px;
    }
    .btn_product_cart, .btn_but_product {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }
    .btn_product_cart {
        background-color: #4CAF50;
        color: white;
    }
    .btn_but_product {
        background-color: #CE5959;
        color: white;
    }
    .favorite-btn {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .favorite-btn.favorited {
        background-color: #b34a4a;
    }
    .favorite-btn ion-icon {
        font-size: 20px;
    }
    </style>
</body>
</html>