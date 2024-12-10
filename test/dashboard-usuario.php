<?php
session_start();

// Verifica se o usuário possui acesso de usuário
if (isset($_SESSION['nivelAcesso']) && $_SESSION['nivelAcesso'] === 'usuario') {
    // Conteúdo do painel de controle do usuário
    echo "Bem-vindo, Usuário!";
} else {
    // Redireciona para a página de login se o usuário não tiver acesso
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Painel de Controle - Usuário</h1>

        <!-- Conteúdo do painel de controle aqui... -->
    </div>
</body>
</html>
