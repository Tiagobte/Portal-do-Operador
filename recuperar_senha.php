<?php
// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se o campo de e-mail foi preenchido
    if (isset($_POST['email'])) {
        // Sanitizar o e-mail
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Verificar se o e-mail está em um formato válido
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Gerar um token único
            $token = bin2hex(random_bytes(32)); // Exemplo de geração de token

            // Conectar-se ao banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "5a2c1234";
            $dbname = "portal_operador";
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar a conexão
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare a consulta SQL para inserir o token na tabela
            $sql = "INSERT INTO password_reset_tokens (email, token) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $token);

            // Execute a consulta
            if ($stmt->execute()) {
                // Enviar e-mail de recuperação de senha
                $to = $email;
                $subject = 'Recuperação de Senha';
                $message = 'Para redefinir sua senha, clique no link a seguir: http://localhost/recuperar_senha.php?token=' . $token;
                $headers = 'From: tiagopereira.tec23@gmail.com' . "\r\n" .
                            'Reply-To: tiagopereira.tec23@gmail.com' . "\r\n" .
                            'X-Mailer: PHP/' . phpversion();

                if (mail($to, $subject, $message, $headers)) {
                    echo 'Um e-mail de recuperação foi enviado para o seu endereço de e-mail. Por favor, verifique sua caixa de entrada.';
                } else {
                    echo 'Erro ao enviar o e-mail de recuperação. Por favor, tente novamente mais tarde.';
                }
            } else {
                echo 'Erro ao salvar o token no banco de dados. Por favor, tente novamente mais tarde.';
            }

            // Feche a conexão com o banco de dados
            $stmt->close();
            $conn->close();
        } else {
            echo 'O endereço de e-mail fornecido não é válido.';
        }
    } else {
        echo 'Por favor, forneça um endereço de e-mail.';
    }
}
?>
