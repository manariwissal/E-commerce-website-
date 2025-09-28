<?php
include_once('./includes/headerNav.php');
require_once './includes/cart.php';

// Traiter les actions sur le panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        removeFromCart($_POST['product_id']);
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['update'])) {
        $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($qty > 0) {
            updateCartQuantity($_POST['product_id'], $qty);
        }
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['product_id'])) { // This condition will handle the initial add to cart from index.php
        $qty = isset($_POST['product_qty']) ? intval($_POST['product_qty']) : 1;
        addToCart($_POST['product_id'], $qty);
        header('Location: cart.php');
        exit;
    }
}
?>

<div class="cart-container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    <h1 style="text-align: center; margin-bottom: 30px;">Votre Panier</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <div style="text-align: center; padding: 40px;">
            <h2>Votre panier est vide</h2>
            <p>Découvrez nos produits et commencez vos achats !</p>
            <a href="index.php" style="
                display: inline-block;
                padding: 12px 24px;
                background-color: #CE5959;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 20px;
            ">Continuer vos achats</a>
        </div>
    <?php else: ?>
        <div style="display: flex; gap: 40px;">
            <!-- Liste des produits -->
            <div style="flex: 2;">
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $item):
                    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
                    
                    if ($product):
                        $subtotal = $product['product_price'] * $item['quantity'];
                        $total += $subtotal;
                ?>
                    <div style="
                        display: flex;
                        gap: 20px;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    ">
                        <img src="./admin/upload/<?php echo htmlspecialchars($product['product_img']); ?>" 
                             alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
                        
                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 10px 0;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </h3>
                            <p style="color: #CE5959; font-weight: bold; margin: 0 0 10px 0;">
                                <?php echo number_format($product['product_price'], 2); ?> MAD
                            </p>
                            
                            <form method="POST" class="cart-update-form" style="display: flex; align-items: center; gap: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="number" name="quantity" class="quantity-input"
                                       data-price="<?php echo $product['product_price']; ?>"
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" style="width: 60px; padding: 5px;">
                                <button type="submit" name="remove" style="
                                    padding: 5px 10px;
                                    background-color: #ff4444;
                                    color: white;
                                    border: none;
                                    border-radius: 4px;
                                    cursor: pointer;">Supprimer</button>
                            </form>
                        </div>
                        
                        <div style="text-align: right;">
                            <p style="font-weight: bold; margin: 0;">
                                Total: <span id="subtotal-<?php echo $product_id; ?>"><?php echo number_format($subtotal, 2); ?> MAD</span>
                            </p>
                        </div>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            
            <!-- Résumé de la commande -->
            <div style="flex: 1;">
                <div style="
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    position: sticky;
                    top: 20px;
                ">
                    <h2 style="margin-top: 0;">Résumé de la commande</h2>
                    <div style="margin: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold;">
                            <span>Total:</span>
                            <span id="total-general">
                                <?php
                                $total_general = 0;
                                foreach ($_SESSION['cart'] as $product_id => $item) {
                                    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                                    $stmt->bind_param("i", $product_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $product = $result->fetch_assoc();
                                    if ($product) {
                                        $sous_total = $product['product_price'] * $item['quantity'];
                                        $total_general += $sous_total;
                                    }
                                }
                                echo number_format($total_general, 2);
                                ?> MAD
                            </span>
                        </div>
                    </div>
                    <a href="checkout.php" style="
                        display: block;
                        padding: 12px;
                        background-color: #CE5959;
                        color: white;
                        text-decoration: none;
                        border-radius: 4px;
                        text-align: center;
                        font-weight: bold;
                        font-size: 1.1em;
                        margin-top: 20px;
                    ">Passer à la commande</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once './includes/footer.php'; ?>
<script>
// Mise à jour AJAX de la quantité
const quantityInputs = document.querySelectorAll('.quantity-input');

quantityInputs.forEach(input => {
  input.addEventListener('change', function() {
    const form = input.closest('.cart-update-form');
    const productId = form.querySelector('input[name="product_id"]').value;
    const quantity = input.value;
    const price = parseFloat(input.dataset.price);
    // Appel AJAX
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('update', '1');
    fetch('cart.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(() => {
      // Mettre à jour le sous-total
      const newSubtotal = (price * quantity).toFixed(2);
      document.getElementById('subtotal-' + productId).textContent = newSubtotal + ' MAD';
      // Recalculer le total général
      let total = 0;
      document.querySelectorAll('.quantity-input').forEach(inp => {
        const unitPrice = parseFloat(inp.dataset.price);
        const qty = parseInt(inp.value);
        if (!isNaN(qty)) {
          total += unitPrice * qty;
        }
      });
      document.getElementById('total-general').textContent = total.toFixed(2) + ' MAD';
    });
  });
});
</script>