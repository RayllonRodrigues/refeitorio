<?php
// password_recovery.php

// Inclua o arquivo de configuração do banco de dados
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere o e-mail do formulário
    $email = $_POST['email'];

    // Verifique se o e-mail está registrado
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // O e-mail está registrado, envie um e-mail com as instruções de recuperação de senha
        // Aqui você pode implementar a lógica para enviar um e-mail com um link de recuperação de senha
        // Por exemplo, você pode gerar um token único e armazená-lo no banco de dados junto com a data de expiração,
        // então envie um e-mail para o usuário com um link que inclui o token, e quando o usuário clicar no link,
        // verifique se o token é válido e permita que ele redefina a senha.

        // Redirecione para a página de confirmação de recuperação de senha
        header('Location: password_recovery_confirmation.php');
        exit;
    } else {
        $error = "O e-mail não está registrado";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .password-recovery-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container password-recovery-container">
        <h2 class="text-center mb-4">Recuperar Senha</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Recuperar Senha</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Voltar para o login</a>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
