<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("localhost", "root", "WJ28@krhps", "db_ecommerce"); // adapte nom_de_ta_base

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
echo "Connexion réussie à la base de données !";
?>
