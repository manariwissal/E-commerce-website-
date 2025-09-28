<?php   include_once('./includes/headerNav.php');

// Configuration PayPal
// Client ID Sandbox pour app paiemnetSiteWeb
define('PAYPAL_CLIENT_ID', 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R');
define('PAYPAL_SECRET', 'EAzITIBryn4Hr9ajL_pn5avM2hgDe3x_CE215X5Pvv0oPvPMPc903rUU-6V_7SilJcy5AF1IB9b4NrJe');

// Configuration des devises
define('SITE_CURRENCY', 'MAD'); // Devise affichée sur le site
define('PAYPAL_CURRENCY', 'EUR'); // Devise pour PayPal (EUR pour Sandbox, MAD pour production)
define('EUR_RATE', 0.092); // Taux de conversion MAD vers EUR

// Fonction de conversion de devise
function convertToPaypalCurrency($amount) {
    return $amount * EUR_RATE;
}

// Fonction de formatage des prix
function formatPrice($amount, $currency = SITE_CURRENCY) {
    return number_format($amount, 2) . ' ' . $currency;
}

// Définir la devise (EUR pour Sandbox, MAD pour production)
define('CURRENCY', 'EUR');

// Activer l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->
<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>

    <style>
        * {
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;

}
:root{
    --main-maroon: #CE5959;
    --deep-maroon: #89375F;
}

.appointments-section {
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 20px;
}

input {
    border: none;
    outline: none;
}

.appointment-heading {
    text-align: center;
}

.appointment-head {
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 0;
    color: var( --main-maroon);
}

.appointment-line {
    width: 160px;
    height: 3px;
    border-radius: 10px;
    background-color: var( --main-maroon);
    display: inline-block;
}

.edit-detail-field .child-detail-inner {
    width: 100%;
    display: flex;
    margin-top: 10px;
    justify-content: space-between;
    margin-left: auto;
    margin-right: auto;
}

.Add-child-section {
    margin-top: 40px;
}

.Add-child-section .child-heading-t {
    font-size: 25px;
    font-weight: 700;
    color: var( --main-maroon);
}

.Add-child-section .child-fields1 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields1 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields1::before {
    position: absolute;
    content: "Prénom";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields1 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields3 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields3 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields3::before {
    position: absolute;
    content: "Nom";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields4 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields4::before {
    position: absolute;
    content: "Numéro ou nom de maison";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields4 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields5 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields5::before {
    position: absolute;
    content: "Rue ou avenue";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields5 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields6 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields6::before {
    position: absolute;
    content: "Ville";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields6 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields7 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields7::before {
    position: absolute;
    content: "Code postal";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields7 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields8 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields8::before {
    position: absolute;
    content: "Numéro de téléphone";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields8 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .child-fields9 {
    width: 49%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .child-fields9::before {
    position: absolute;
    content: "Adresse e-mail";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields9 input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    background-color: transparent;
}

.Add-child-section .Address-field {
    width: 100%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
}

.Add-child-section .Address-field input {
    color: #000000;
    font-weight: 700;
    width: 100%;
    height: 100%;
}

.Add-child-section .Address-field::before {
    position: absolute;
    content: "Pays";
    top: -10px;
    background-image: linear-gradient(#FFFCF6, #FFFFFF);
    padding-left: 4px;
    padding-right: 4px;
    color: var( --main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-dob p {
    font-size: 25px;
    font-weight: 700;
    color: var( --main-maroon);
}

.Add-child-section .dob-fields {
    margin-top: 20px;
    width: 60%;
    height: 55px;
    border: 1px solid var( --main-maroon);
    border-radius: 5px;
    margin-bottom: 30px;
    padding: 15px;
    background-color: #FFFFFF;
    padding-right: 30px;
    position: relative;
    box-shadow: 2px 2px 2px rgb(185, 184, 184);
    display: flex;
    justify-content: space-between;
}

.Add-child-section .dob-fields span svg {
    width: 25px;
    height: 25px;
    margin-left: auto;
}

.Add-child-section .dob-fields input {
    color: #000000;
    font-weight: 700;
    width: 80%;
    background-color: transparent;
}

.child-register-btn {
    padding-top: 5px;
}

.child-register-btn p {
    width: 550px;
    height: 60px;
    background-color: var( --main-maroon);
    box-shadow: 0px 0px 4px #615f5f;
    line-height: 60px;
    color: #FFFFFF;
    margin-left: auto;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    font-weight: 600;
}

.Add-child-section .Address-field textarea {
    display: none;
}

@media screen and (max-width: 794px) {


    .child-register-btn p {
        width: 100%;
    }

    .edit-detail-field .child-heading-t {
        margin-bottom: 25px;
    }

    .edit-detail-field .child-detail-inner {
        width: 100%;
        display: flex;
        flex-direction: column;
        margin-top: 0px;
        justify-content: unset;
    }

    .child-fields,
    .child-fields1,
    .child-fields3 {
        width: 100% !important;
    }

    .dob-fields {
        width: 100% !important;
    }
}

@media screen and (max-width: 629px) {
    .Add-child-section {
        width: 80%;
        margin-left: auto;
        margin-right: auto;
    }
}

@media screen and (max-width: 409px) {
    .Add-child-section {
        width: 90%;
        margin-left: auto;
        margin-right: auto;
    }
}

@media screen and (max-width:628px) {
    .appointments-section {
        width: 100% !important;

    }

}

.error-ms {
    color: var( --main-maroon);
    margin-bottom: 10px;
}
    </style>
</header>

<div class="overlay" data-modal-overlay></div>

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
    - MAIN
  -->

<main>
    <div class="appointments-section">
        <div class="appointment-heading">
            <p class="appointment-head">CheckOut</p>
            <span class="appointment-line"></span>
        </div>
        <div class="inner-appointment">
            <form method="post">
                <section class="edit-detail-field">
                    <div class="Add-child-section">
                        <!-- Informations client -->
                        <div class="customer-info">
                            <h3 class="child-heading-t">Informations de livraison</h3>
                            <div class="child-detail-inner">
                                <div class="child-fields1">
                                    <input type="text" id="firstName" name="firstName" style="color: #676767;" placeholder="Prénom" required>
                                </div>
                                <div class="child-fields3">
                                    <input type="text" id="lastName" name="lastName" style="color: #676767;" placeholder="Nom" required>
                                </div>
                            </div>
                            <div class="child-detail-inner">
                                <div class="child-fields child-fields4">
                                    <input type="text" id="houseNumber" name="houseNumber" placeholder="Numéro ou nom de maison" required>
                                </div>
                                <div class="child-fields child-fields5">
                                    <input type="text" id="street" name="street" placeholder="Rue ou avenue" required>
                                </div>
                            </div>
                            <div class="child-detail-inner">
                                <div class="child-fields child-fields6">
                                    <input type="text" id="city" name="city" placeholder="Ville" required>
                                </div>
                                <div class="child-fields child-fields7">
                                    <input type="text" id="postalCode" name="postalCode" placeholder="Code postal" required>
                                </div>
                            </div>
                            <div class="child-detail-inner">
                                <div class="child-fields Address-field">
                                    <input type="text" id="country" name="country" style="color: #676767;" placeholder="Pays" required>
                                </div>
                            </div>
                            <div class="child-detail-inner">
                                <div class="child-fields child-fields8">
                                    <input type="text" id="phone" name="phone" placeholder="Numéro de téléphone" required>
                                </div>
                                <div class="child-fields child-fields9">
                                    <input type="email" id="email" name="email" placeholder="Adresse e-mail" required>
                                </div>
                            </div>
                        </div>
                        <!-- Résumé de la commande -->
                        <div class="payment-options" style="margin-top: 30px; margin-bottom: 30px;">
                            <h3 class="child-heading-t">Résumé de la commande</h3>
                            <div class="order-summary" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                                <div id="order-items" style="margin: 10px 0;">
                                    <?php
                                    $total = 0;
                                    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                                        foreach ($_SESSION['cart'] as $product_id => $item) {
                                            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                                            $stmt->bind_param("i", $product_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $product = $result->fetch_assoc();
                                            if ($product) {
                                                $subtotal = $product['product_price'] * $item['quantity'];
                                                $total += $subtotal;
                                    ?>
                                    <div class="cart-item" style="display: flex; align-items: center; margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #eee;">
                                        <img src="./admin/upload/<?php echo htmlspecialchars($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_title']); ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                        <div style="flex-grow: 1;">
                                            <h4 style="margin: 0;"><?php echo htmlspecialchars($product['product_title']); ?></h4>
                                            <p style="margin: 5px 0;">Quantité: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <div style="font-weight: bold;">
                                            <?php echo number_format($subtotal, 2); ?> MAD
                                        </div>
                                    </div>
                                    <?php
                                            }
                                        }
                                    } else {
                                        echo '<p>Votre panier est vide.</p>';
                                    }
                                    ?>
                                </div>
                                <div style="border-top: 1px solid #ddd; margin-top: 10px; padding-top: 10px;">
                                    <div style="display: flex; justify-content: space-between; font-weight: bold;">
                                        <span>Total :</span>
                                        <span><?php echo number_format($total, 2); ?> MAD</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Options de paiement -->
                            <div class="payment-methods">
                                <h3 class="child-heading-t">Méthode de Paiement</h3>
                                <p style="font-size: 18px; color: #CE5959; font-weight: bold; margin-top: 20px;">Le paiement se fait à la livraison.</p>
                            </div>
                        </div>
                        <div class="child-register-btn">
                            <input type="hidden" name="checkout_submit" value="1">
                            <button type="submit" id="validate-order-btn" class="btn btn-success btn-lg w-100" style="font-size: 1.2rem; font-weight: 600;">Valider la commande</button>
                            <span class="error-ms"></span>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</main>

<?php require_once './includes/footer.php'; ?>

<!-- Toast message -->
<div id="order-toast" style="display:none;position:fixed;top:30px;left:50%;transform:translateX(-50%);background:#25D366;color:#fff;padding:16px 32px;border-radius:8px;z-index:9999;font-size:1.2em;font-weight:bold;">
    La commande a été bien envoyée !
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const btn = document.getElementById('validate-order-btn');
    const toast = document.getElementById('order-toast');

    if (!form || !btn || !toast) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = "Traitement...";

        // Envoi AJAX
        const formData = new FormData(form);
        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                toast.style.display = 'block';
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1500);
            } else {
                btn.disabled = false;
                btn.textContent = "Valider la commande";
                alert('Erreur lors de la validation de la commande.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = "Valider la commande";
            alert('Erreur lors de la validation de la commande.');
        });
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout_submit'])) {
    // Récupérer les infos client (à adapter selon tes champs)
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = ($_POST['houseNumber'] ?? '') . ' ' . ($_POST['street'] ?? '') . ', ' . ($_POST['city'] ?? '') . ', ' . ($_POST['country'] ?? '') . ' ' . ($_POST['postalCode'] ?? '');
    $customer_id = $_SESSION['userid'] ?? null;
    if (!$customer_id) {
        // Rediriger vers login si pas connecté
        header('Location: login.php');
        exit();
    }
    $total = 0;
    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt = $conn->prepare("SELECT product_price FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            if ($product) {
                $total += $product['product_price'] * $item['quantity'];
            }
        }
    }
    // Insérer la commande avec infos de livraison
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_date, order_status, total_amount, delivery_address, delivery_phone, delivery_email, is_seen) VALUES (?, NOW(), 'en cours', ?, ?, ?, ?, 0)");
    $stmt->bind_param("idsss", $customer_id, $total, $address, $phone, $email);
    if (!$stmt->execute()) {
        die('Erreur SQL : ' . $stmt->error);
    }
    $order_id = $conn->insert_id;
    // Enregistrer l'action utilisateur (commande passée)
    $action_type = 'order_placed';
    $null_product = NULL;
    $stmt_action = $conn->prepare("INSERT INTO user_actions (customer_id, action_type, product_id, action_time) VALUES (?, ?, ?, NOW())");
    $stmt_action->bind_param("isi", $customer_id, $action_type, $null_product);
    $stmt_action->execute();
    // Insérer les produits de la commande
    if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            // Récupérer le prix du produit depuis la base
            $stmt_price = $conn->prepare("SELECT product_price FROM products WHERE product_id = ?");
            $stmt_price->bind_param("i", $product_id);
            $stmt_price->execute();
            $result_price = $stmt_price->get_result();
            $product = $result_price->fetch_assoc();
            $price = $product ? $product['product_price'] : 0;

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $product_id, $item['quantity'], $price);
            $stmt->execute();
        }
    }
    // Vider le panier
    unset($_SESSION['cart']);
    echo 'success';
    exit();
}
?>
