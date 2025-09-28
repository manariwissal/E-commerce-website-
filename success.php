<?php
include_once('./includes/headerNav.php');

$payment_id = $_GET['payment_id'] ?? null;
?>

<div class="appointments-section">
    <div class="appointment-heading">
        <p class="appointment-head">Confirmation de Paiement</p>
        <span class="appointment-line"></span>
    </div>

    <div class="inner-appointment" style="text-align: center; padding: 40px;">
        <?php if ($payment_id): ?>
            <div style="color: #28a745; font-size: 48px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="color: #28a745; margin-bottom: 20px;">Paiement Réussi !</h2>
            <p style="font-size: 18px; margin-bottom: 30px;">
                Merci pour votre commande. Votre paiement a été traité avec succès via PayPal.
            </p>
            <p style="font-size: 16px; color: #666;">
                Numéro de transaction : <?php echo htmlspecialchars($payment_id); ?>
            </p>
        <?php else: ?>
            <div style="color: #dc3545; font-size: 48px; margin-bottom: 20px;">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2 style="color: #dc3545; margin-bottom: 20px;">Erreur de Paiement</h2>
            <p style="font-size: 18px; margin-bottom: 30px;">
                Désolé, une erreur s'est produite lors du traitement de votre paiement.
            </p>
        <?php endif; ?>

        <div style="margin-top: 40px;">
            <a href="index.php" style="
                display: inline-block;
                padding: 12px 30px;
                background-color: #CE5959;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            ">Retour à l'accueil</a>
        </div>
    </div>
</div>

<?php require_once './includes/footer.php'; ?> 