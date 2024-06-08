<?php
session_start(); // Inicie a sessão antes de acessar as variáveis de sessão

if (!isset($_SESSION['username'])) {
    // Se 'username' não estiver definido na sessão, redirecione para a página de login
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px 20px;
        }
        .jumbotron {
            background-color: transparent;
            text-align: center;
            padding-top: 100px;
            padding-bottom: 50px;
        }
        .jumbotron h1 {
            font-size: 48px;
            font-weight: 700;
            color: #333;
        }
        .jumbotron p {
            font-size: 18px;
            font-weight: 400;
            color: #666;
            margin-top: 20px;
        }
        .btn {
            border-radius: 25px;
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
        .logout-btn {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        .logout-btn:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px;
            border-radius: 5px;
        }
        .navbar .nav-btn {
            background-color: #fff;
            color: #007bff;
            border: 1px solid #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .navbar .nav-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }
        .user-info {
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Portal Operador Remoto</h1>
        </div>
        <nav class="navbar">
            <div>
                <button class="nav-btn" onclick="window.location.href='registros_eventos.html'">Registro De Eventos</button>
                <button class="nav-btn" onclick="window.location.href='escala_sobreaviso.html'">Escala de Sobreaviso</button>
                <button class="nav-btn" onclick="window.location.href='lista_telefonica.html'">Lista Telefônica</button>
            </div>
            <div>
                <span class="user-info">Olá, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <button class="nav-btn logout-btn" onclick="logout()">Logout</button>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Bem-vindo ao Portal do Operador</h1>
            <p class="lead">Este é um portal destinado aos operadores de usinas elétricas.</p>
            </div>
            <!-- Outros cards aqui -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>
</html>
