<?php
session_start();
require 'db.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta o banco de dados para verificar as credenciais
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Credenciais corretas
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Credenciais incorretas
        echo "<script>alert('Nome de usuário ou senha incorretos!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        .form-control {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <p>Por favor, preencha suas credenciais para fazer login.</p>
        <form id="login-form" action="login.php" method="post">
            <div class="form-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>    
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-custom" value="Login">
            </div>
            <p>Não tem uma conta? <a href="register.php">Registre-se aqui</a>.</p>
        </form>
    </div>
</body>
</html>
