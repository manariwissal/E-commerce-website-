<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'includes/config.php';

// Get all products
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Display products
echo "<h1>Liste des produits</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Titre</th><th>Prix</th><th>Image</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['product_id'] . "</td>";
    echo "<td>" . htmlspecialchars($row['product_title']) . "</td>";
    echo "<td>" . $row['product_price'] . "</td>";
    echo "<td>" . $row['product_img'] . "</td>";
    echo "</tr>";
}

echo "</table>";

// Display table structure
$sql = "DESCRIBE product";
$result = mysqli_query($conn, $sql);

echo "<h2>Structure de la table</h2>";
echo "<table border='1'>";
echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}

echo "</table>";
?> 