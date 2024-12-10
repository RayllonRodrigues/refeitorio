<?php
// dashboard.php

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['cpf'])) {
    header('Location: login.php');
    exit;
}

// Verifica o papel do usuário
$role = $_SESSION['role'];

// Determina o menu a ser exibido com base no papel do usuário
$menu = '';

if ($role === 'administrador') {
    $menu = '
        <li><a href="admin.php">Página Inicial</a></li>
        <li><a href="gerenciar_usuarios.php">Gerenciar Usuários</a></li>
        <li><a href="gerenciar_refeicoes.php">Gerenciar Refeições</a></li>
        <li><a href="relatorios.php">Relatórios</a></li>
        <li><a href="logout.php">Sair</a></li>
    ';
} elseif ($role === 'gerente') {
    $menu = '
        <li><a href="manager.php">Página Inicial</a></li>
        <li><a href="gerenciar_refeicoes.php">Gerenciar Refeições</a></li>
        <li><a href="relatorios.php">Relatórios</a></li>
        <li><a href="logout.php">Sair</a></li>
    ';
} else {
    $menu = '
        <li><a href="user.php">Página Inicial</a></li>
        <li><a href="fazer_pedido.php">Fazer Pedido</a></li>
        <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
        <li><a href="logout.php">Sair</a></li>
    ';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Sistema de Refeitório</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php echo $menu; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Bem-vindo(a) ao Dashboard</h1>
        <p>Seu papel: <?php echo $role; ?></p>
        <p>Aqui você encontrará informações e recursos específicos para o seu papel.</p>
    </div>
</body>
</html>
