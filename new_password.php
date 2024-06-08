<?php
require 'db.php';

$token = $_GET['token'];
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = $token_err = "";

// Verificar se o token é válido
if (empty($token)) {
    $token_err = "Token inválido ou ausente.";
} else {
    $sql = "SELECT * FROM password_reset_tokens WHERE token = ? AND created_at >= NOW() - INTERVAL 1 HOUR";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $param_token);
        $param_token = $token;
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $email = $row['email'];
            } else {
                $token_err = "Token inválido ou expirado.";
            }
        } else {
            echo "Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        $stmt->close();
    }
}

// Processar o formulário de redefinição de senha
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($token_err)) {
    // Validar nova senha
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Por favor, insira a nova senha.";     
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validar confirmação de senha
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor, confirme a senha.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "As senhas não coincidem.";
        }
    }

    // Verificar erros de entrada antes de atualizar a senha no banco de dados
    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_password, $param_email);
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_email = $email;
            
            if ($stmt->execute()) {
                // Excluir o token usado
                $sql = "DELETE FROM password_reset_tokens WHERE email = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("s", $param_email);
                    $param_email = $email;
                    $stmt->execute();
                }

                echo "Sua senha foi redefinida com sucesso.";
                header("Location: login.php");
                exit();
            } else {
                echo "Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha</title>
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
    .reset-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    .reset-container h1 {
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
  <div class="reset-container">
    <h1>Redefinir Senha</h1>
    <p>Por favor, insira sua nova senha.</p>
    <?php 
    if(!empty($token_err)){
        echo '<div class="alert alert-danger">' . $token_err . '</div>';
    }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=" . $token; ?>" method="post">
      <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
        <label>Nova Senha</label>
        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
      </div>
      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
        <label>Confirme a Nova Senha</label>
        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary btn-custom" value="Redefinir Senha">
      </div>
    </form>
  </div>
</body>
</html>
