<?php
require 'db.php';

$email = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, insira seu email.";
    } else {
        $email = trim($_POST["email"]);
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $token = bin2hex(random_bytes(32));
                    $sql = "INSERT INTO password_reset_tokens (email, token) VALUES (?, ?)";
                    
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ss", $param_email, $param_token);
                        $param_email = $email;
                        $param_token = $token;
                        
                        if ($stmt->execute()) {
                            $reset_link = "http://yourdomain.com/new_password.php?token=$token";
                            mail($email, "Recuperação de Senha", "Clique no link para redefinir sua senha: $reset_link");
                            echo "Um link de recuperação de senha foi enviado para seu email.";
                        } else {
                            echo "Algo deu errado. Por favor, tente novamente mais tarde.";
                        }
                    }
                } else {
                    $email_err = "Nenhuma conta encontrada com este email.";
                }
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
  <title>Recuperar Senha</title>
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
    <h1>Recuperar Senha</h1>
    <p>Por favor, insira seu email para recuperar sua senha.</p>
    <?php 
    if(!empty($email_err)){
        echo '<div class="alert alert-danger">' . $email_err . '</div>';
    }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
      </div>    
      <div class="form-group">
        <input type="submit" class="btn btn-primary btn-custom" value="Enviar">
      </div>
    </form>
  </div>
</body>
</html>
