<?php
// Carregar o autoload do Composer
require __DIR__ . '/vendor/autoload.php';

// Carregar variáveis de ambiente do arquivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Usar variáveis de ambiente
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Estabelecer a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("A conexão com o banco de dados falhou. Por favor, tente novamente mais tarde.");
}

// Função para fechar a conexão, caso necessário
function closeConnection($conn) {
    $conn->close();
}

// Certifique-se de fechar a conexão ao final do script ou onde for apropriado
// closeConnection($conn);
