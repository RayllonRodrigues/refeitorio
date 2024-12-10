<?php
// profile.php

// Inclua o arquivo de configuração do banco de dados
require_once 'config.php';

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

// Recupere os dados do usuário do banco de dados
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $email = $row['email'];
} else {
    // Caso ocorra algum erro na recuperação dos dados do usuário
    $error = "Erro ao recuperar dados do usuário";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .profile-container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <h2 class="text-center mb-4">Perfil do Usuário</h2>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" class="form-control" value="<?php echo $name; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" class="form-control" value="<?php echo $email; ?>" readonly>
            </div>
        <?php endif; ?>
        <a href="password_change.php" class="btn btn-primary btn-block">Alterar Senha</a>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
