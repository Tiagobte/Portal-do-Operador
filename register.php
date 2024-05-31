<?php
require 'db.php';

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar nome de usuário
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, insira o nome de usuário.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $username_err = "Este nome de usuário já está em uso.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            $stmt->close();
        }
    }

    // Validar e-mail
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, insira o e-mail.";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Formato de e-mail inválido.";
        }
    }

    // Validar senha
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, insira uma senha.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validar confirmação de senha
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor, confirme a senha.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "As senhas não coincidem.";
        }
    }

    // Verificar erros de entrada antes de inserir no banco de dados
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
         
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_username, $param_password, $param_email);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            
            if ($stmt->execute()) {
                header("Location: login.php");
            } else {
                echo "Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar</title>
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
    .register-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .register-container h1 {
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
  <div class="register-container">
    <h1>Registrar</h1>
    <p>Por favor, preencha este formulário para criar uma conta.</p>
    <?php 
    if(!empty($username_err) || !empty($password_err) || !empty($confirm_password_err) || !empty($email_err)){
        echo '<div class="alert alert-danger">' . $username_err . '<br>' . $password_err . '<br>' . $confirm_password_err . '<br>' . $email_err . '</div>';
    }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
        <label>Nome de Usuário</label>
        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
      </div>    
      <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
      </div>  
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
        <label>Senha</label>
        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
      </div>
      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
        <label>Confirme a Senha</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary btn-custom" value="Registrar">
      </div>
      <p>Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
    </form>
  </div>
</body>
</html>
