<?php
$servername = "localhost";
$username = "root"; // Seu usuário MySQL
$password = "5a2c1234"; // Sua senha MySQL
$dbname = "portal_operador";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
