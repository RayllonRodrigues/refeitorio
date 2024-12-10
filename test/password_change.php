<?php
// password_change.php

// Inclua o arquivo de configuração do banco de dados
require_once 'config.php';

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupere os dados do formulário
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verifique se a nova senha e a confirmação correspondem
    if ($newPassword !== $confirmPassword) {
        $error = "A nova senha e a confirmação não correspondem";
    } else {
        // Verifique se a senha atual está correta
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$oldPassword'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            // Atualize a senha do usuário
            $updateSql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
            $conn->query($updateSql);

            // Redirecione para a página de perfil
            header('Location: profile.php');
            exit;
        } else {
            $error = "Senha atual incorreta";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .password-change-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container password-change-container">
        <h2 class="text-center mb-4">Alterar Senha</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="password" name="old_password" class="form-control" placeholder="Senha Atual" required>
            </div>
            <div class="form-group">
                <input type="password" name="new_password" class="form-control" placeholder="Nova Senha" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar Nova Senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Alterar Senha</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
