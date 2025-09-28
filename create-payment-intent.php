<?php
require_once 'includes/stripe-config.php';

header('Content-Type: application/json');

try {
    // Récupérer le montant depuis la requête POST
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    if (!isset($jsonObj->amount)) {
        throw new Exception('Montant manquant');
    }

    // Créer le PaymentIntent
    $paymentIntent = createPaymentIntent($jsonObj->amount);

    if (isset($paymentIntent['error'])) {
        throw new Exception($paymentIntent['error']);
    }

    // Renvoyer le client secret
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 