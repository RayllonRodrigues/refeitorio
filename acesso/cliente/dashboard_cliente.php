<?php
session_start();
require_once '../../config.php'; // Corrigido o caminho para o arquivo config.php

// Verificar se o usuário está autenticado como cliente
if (!isset($_SESSION['user_id']) || $_SESSION['nivel_acesso'] !== 'cliente') {
    header("Location: Location:/refeitorio/acesso/cliente/dashboard_cliente.php"); // Corrigido o caminho para redirecionamento ao login.php
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Refeitório - Painel do Aluno</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/refeitorio/acesso/cliente/dashboard_cliente.php">Sistema de Refeitório</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/acesso/cliente/perfil_cliente.php"><i class="fas fa-home"></i> Carteira Digital</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/acesso/cliente/cardapio_cliente.php"><i class="fas fa-users"></i>Cardápio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-cog"></i> Informativos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/acesso/cliente/alterar_senha.php"><i class="fas fa-cog"></i> Alterar Senha</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/refeitorio/login.php">Sair</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2><i class="fas fa-tachometer-alt"></i> Bem-vindo ao Painel Aluno</h2>













        
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
