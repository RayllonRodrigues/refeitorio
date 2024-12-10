<?php
// register.php

// Inclua o arquivo de configuração do banco de dados
require_once 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verifique se a senha e a confirmação correspondem
    if ($password !== $confirmPassword) {
        $error = "A senha e a confirmação não correspondem";
    } else {
        // Verifique se o e-mail já está registrado
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "O e-mail já está registrado";
        } else {
            // Insira o novo usuário no banco de dados
            $insertSql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            $conn->query($insertSql);

            // Redirecione para a página de login
            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .register-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <h2 class="text-center mb-4">Registro</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Nome" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Senha" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar Senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Já tem uma conta? Faça login</a>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
