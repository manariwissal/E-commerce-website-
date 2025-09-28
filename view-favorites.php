<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
require_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

// Get user's favorite products
$sql = "SELECT p.* FROM product p 
        INNER JOIN favorites f ON p.product_id = f.product_id 
        WHERE f.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['userid']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - HCA E-Commerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="./css/style-prefix.css">
    <link rel="stylesheet" type="text/css" href="./css/view-details.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <h1 class="section-title">Mes Favoris</h1>

        <div class="products-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="price"><?php echo number_format($product['price'], 2); ?> €</p>
                            <div class="product-actions">
                                <button class="btn-remove-favorite" data-product-id="<?php echo $product['product_id']; ?>">
                                    <i class="fas fa-heart"></i> Retirer des favoris
                                </button>
                                <a href="view-details.php?id=<?php echo $product['product_id']; ?>" class="btn-view-details">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-favorites">Vous n'avez pas encore de produits favoris.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle remove from favorites
        document.querySelectorAll('.btn-remove-favorite').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                
                fetch('includes/favorites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        'action': 'remove',
                        'product_id': productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the product card from the DOM
                        this.closest('.product-card').remove();
                        
                        // If no more favorites, show message
                        if (document.querySelectorAll('.product-card').length === 0) {
                            document.querySelector('.products-grid').innerHTML = 
                                '<p class="no-favorites">Vous n\'avez pas encore de produits favoris.</p>';
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            });
        });
    });
    </script>
</body>
</html> 