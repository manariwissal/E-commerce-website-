<?php
$password = 'ayoub123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Mot de passe haché pour 'ayoub123' : " . $hashedPassword . "\n";
?> 