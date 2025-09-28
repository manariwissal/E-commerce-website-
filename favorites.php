<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once('./includes/headerNav.php');
// You might want to include config.php and functions.php if needed for content
include_once('./includes/config.php');
require_once('./includes/functions.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit;
}

try {
    // Récupérer les produits favoris
    $favorites = getUserFavorites($_SESSION['userid']);
    
    if ($favorites === false) {
        throw new Exception("Erreur lors de la récupération des favoris");
    }
} catch (Exception $e) {
    // Log l'erreur
    error_log("Erreur dans favorites.php: " . $e->getMessage());
    $error_message = "Une erreur est survenue lors du chargement de vos favoris.";
}

function getUserFavorites($user_id) {
    global $conn;
    $sql = "SELECT p.product_id, p.product_title, p.product_img, p.product_price FROM products p INNER JOIN favorites f ON p.product_id = f.product_id WHERE f.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des favoris</title>
    <style>
        body {
            background: linear-gradient(135deg, #f8f8f8 60%, #ffeaea 100%);
            margin: 0;
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
        }
        .favorites-title {
            text-align: center;
            color: #a1001a;
            font-size: 2.8rem;
            font-weight: 800;
            margin-top: 40px;
            margin-bottom: 30px;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #fff2f2;
        }
        .favorites-list {
            max-width: 700px;
            margin: 0 auto 40px auto;
        }
        .favorite-item {
            display: flex;
            align-items: center;
            gap: 24px;
            padding: 18px 20px;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 2px 12px #e6e6e6;
            margin-bottom: 22px;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .favorite-item:hover {
            box-shadow: 0 6px 24px #e6b6b6;
            transform: translateY(-2px) scale(1.01);
        }
        .favorite-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ffeaea;
        }
        .favorite-info {
            flex: 1;
        }
        .favorite-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 6px;
            color: #a1001a;
        }
        .favorite-price {
            color: #a1001a;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .remove-fav-btn {
            background: none;
            border: none;
            color: #a1001a;
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .remove-fav-btn:hover {
            color: #ce5959;
        }
        .no-favorites {
            text-align: center;
            color: #888;
            font-size: 1.2rem;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="favorites-title">Liste des favoris</div>
    <div class="favorites-list">
        <?php if (isset($favorites) && $favorites->num_rows > 0): ?>
            <?php while ($product = $favorites->fetch_assoc()): ?>
                <div class="favorite-item">
                    <img src="./admin/upload/<?php echo htmlspecialchars($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_title']); ?>">
                    <div class="favorite-info">
                        <div class="favorite-title"> <?php echo htmlspecialchars($product['product_title']); ?> </div>
                        <div class="favorite-price"> $<?php echo number_format($product['product_price'],2); ?> </div>
                    </div>
                    <form method="post" action="remove_favorite.php" style="margin:0;">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" class="remove-fav-btn" title="Retirer des favoris">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-favorites">Aucun favori pour le moment.</p>
        <?php endif; ?>
    </div>
</body>
</html> 