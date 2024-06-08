<?php
require 'db.php'; // Importar arquivo db.php que define as variáveis de conexão

// Iniciar a sessão
session_start();

// Se o usuário estiver autenticado, redirecionar para index.php
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Se o formulário de login for enviado, verificar credenciais
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar o banco de dados para verificar as credenciais
    $sql = "SELECT * FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Credenciais corretas, iniciar a sessão
                    session_start();
                    $_SESSION['username'] = $username;
                    header("Location: index.php");
                    exit();
                } else {
                    $login_err = "Credenciais inválidas. Por favor, tente novamente.";
                }
            } else {
                $login_err = "Credenciais inválidas. Por favor, tente novamente.";
            }
        } else {
            echo "Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        $stmt->close();
    }
    $conn->close();
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
        /* Estilos CSS aqui */
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php 
        if(isset($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Usuário</label>
                <input type="text" name="username" class="form-control">
            </div>    
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
